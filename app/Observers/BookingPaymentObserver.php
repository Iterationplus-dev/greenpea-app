<?php

namespace App\Observers;

use App\Events\BookingPaid;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\BookingPayment;
use App\Events\PaymentReceived;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;

class BookingPaymentObserver
{
    /**
     * Handle the BookingPayment "created" event.
     */
    public function created(BookingPayment $bookingPayment): void
    {
        // Only process successful payments
        if (! in_array($bookingPayment->status, [
            PaymentStatus::SUCCESS->value,
            PaymentStatus::PAID->value,
        ])) {
            return;
        }

        DB::transaction(function () use ($bookingPayment) {

            $booking = $bookingPayment
                ->booking()
                ->lockForUpdate()
                ->first();

            // Ensure invoice exists on first payment
            if (! $booking->invoice) {
                app(InvoiceService::class)
                    ->generateForBooking($booking);

                // Reload relationship after creation
                $booking->load('invoice');
            }

            // Refresh invoice totals BEFORE events
            app(InvoiceService::class)
                ->refreshInvoiceTotals($booking->invoice);

            // Dispatch payment received event
            event(new PaymentReceived($bookingPayment));

            // Determine if fully paid
            $paidAmount = $booking->paidAmount();

            if ($paidAmount >= $booking->net_amount) {

                // Dispatch BookingPaid only once
                if (! $booking->is_fully_paid) {

                    $booking->update([
                        'is_fully_paid' => true,
                        'paid_at' => now(),
                    ]);

                    event(new BookingPaid($booking));
                }
            } else {

                // Mark as not fully paid
                $booking->update([
                    'is_fully_paid' => false,
                ]);
            }
        });
    }


    /**
     * Handle the BookingPayment "updated" event.
     */
    public function updated(BookingPayment $bookingPayment): void
    {
        if ($bookingPayment->wasChanged('status')) {
            $this->created($bookingPayment->fresh());
        }
    }

    /**
     * Handle the BookingPayment "deleted" event.
     */
    public function deleted(BookingPayment $bookingPayment): void
    {
        //
    }

    /**
     * Handle the BookingPayment "restored" event.
     */
    public function restored(BookingPayment $bookingPayment): void
    {
        //
    }

    /**
     * Handle the BookingPayment "force deleted" event.
     */
    public function forceDeleted(BookingPayment $bookingPayment): void
    {
        //
    }
}

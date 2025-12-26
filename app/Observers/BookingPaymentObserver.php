<?php

namespace App\Observers;

use App\Events\BookingPaid;
use App\Enums\PaymentStatus;
use App\Models\BookingPayment;
use App\Events\PaymentReceived;
use Illuminate\Support\Facades\DB;

class BookingPaymentObserver
{
    /**
     * Handle the BookingPayment "created" event.
     */
    public function created(BookingPayment $bookingPayment): void
    {
        // Only act on successful payments
        if ($bookingPayment->status !== PaymentStatus::PAID) {
            return;
        }

        DB::transaction(function () use ($bookingPayment) {

            $booking = $bookingPayment->booking()->lockForUpdate()->first();

            // 1️⃣ Dispatch payment received event
            event(new PaymentReceived($bookingPayment));

            // 2️⃣ Update booking payment status
            $paidAmount = $booking->paidAmount();

            if ($paidAmount >= $booking->net_amount) {

                // Prevent double-processing
                if ($booking->status !== PaymentStatus::PAID) {
                    $booking->update(['status' => PaymentStatus::PAID]);

                    // 3️⃣ Dispatch booking paid event (ONLY ONCE)
                    event(new BookingPaid($booking));
                }
            } else {
                $booking->update(['status' => PaymentStatus::PARTIALLY_PAID]);
            }
        });
    }

    /**
     * Handle the BookingPayment "updated" event.
     */
    public function updated(BookingPayment $bookingPayment): void
    {
        //
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

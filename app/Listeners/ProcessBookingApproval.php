<?php

namespace App\Listeners;

use App\Events\BookingApproved;
use App\Services\InvoiceService;
use App\Services\PaystackService;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessBookingApproval
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingApproved $event): void
    {
        DB::transaction(function () use ($event) {
            $booking = $event->booking->fresh();

            // Safety: never double process
            if ($booking->payment_link) {
                return;
            }

            // 1. Lock the apartment
            $booking->apartment->update([
                'is_available' => false,
            ]);

            // 2. Generate invoice
            $invoice = app(InvoiceService::class)
                ->createForBooking($booking);

            // 3. Create Paystack payment
            $payment = app(PaystackService::class)
                ->createBookingPayment($booking, $invoice);

            // 4. Store payment link
            $booking->update([
                // 'payment_link' => $payment->authorization_url,
                'payment_link' => $payment->response['authorization_url'],
            ]);
        });
    }
}

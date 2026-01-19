<?php

namespace App\Listeners;

use App\Mail\InvoiceMail;
use App\Events\BookingApproved;
use App\Services\InvoiceService;
use App\Services\PaystackService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

            // Lock the apartment
            $booking->apartment->update([
                'is_available' => false,
            ]);

            // 2. Generate invoice
            $invoice = app(InvoiceService::class)
                ->generateForBooking($booking);

            // Send invoice email immediately
            Mail::to($booking->guest_email)
                ->send(new InvoiceMail($invoice));

            //Create Paystack payment
            $payment = app(PaystackService::class)
                ->createBookingPayment($booking, $invoice);

            //Store payment link
            $booking->update([
                'payment_link' => $payment->response['authorization_url'],
            ]);
        });
    }
}

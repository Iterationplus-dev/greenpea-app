<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Events\PaymentReceived;
use App\Services\WalletService;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessPaymentReceived
{
    public function handle(PaymentReceived $event)
    {
        DB::transaction(function () use ($event) {

            $payment = $event->payment->fresh();

            // Only act on successful payments
            if ($payment->status !== PaymentStatus::SUCCESS->value) {
                return;
            }

            $booking = $payment->booking;

            // At this point, observer has already ensured invoice exists
            $invoice = $booking->invoice;

            if (! $invoice) {
                return;
            }

            // Credit owner wallet
            app(WalletService::class)
                ->creditOwnerForBooking($booking);


            // Send receipt if invoice is now fully paid
            if ($invoice && $invoice->balance_due <= 0 && ! $booking->receipt_sent_at) {
                Mail::to($booking->guest_email)
                    ->send(new PaymentReceiptMail($invoice));

                $booking->update([
                    'receipt_sent_at' => now()
                ]);
            }
        });
    }
}

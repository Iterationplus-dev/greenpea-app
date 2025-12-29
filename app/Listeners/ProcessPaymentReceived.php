<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use Illuminate\Support\Facades\DB;
use App\Services\WalletService;
use App\Services\InvoiceService;

class ProcessPaymentReceived
{
    public function __construct() {}
    public function handle(PaymentReceived $event)
    {
        DB::transaction(function () use ($event) {

            $payment = $event->payment->fresh();

            // Safety
            if ($payment->status !== 'success') {
                return;
            }

            $booking = $payment->booking;
            $invoice = $booking->invoice;

            // 1. Mark invoice paid
            app(InvoiceService::class)->markAsPaid($invoice);

            // 2. Credit property owner
            app(WalletService::class)->creditOwnerForBooking($booking);

            // 3. Mark booking fully paid
            $booking->update([
                'is_fully_paid' => true,
                'paid_at' => now(),
            ]);

            //
            app(InvoiceService::class)->markAsPaid($invoice);
            app(InvoiceService::class)->finalizeInvoice($invoice);
        });
    }
}

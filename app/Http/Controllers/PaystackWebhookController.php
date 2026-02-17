<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Events\PaymentReceived;
use App\Models\BookingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaystackWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Verify signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();
        $secret = config('services.paystack.secret');

        if ($signature !== hash_hmac('sha512', $payload, $secret)) {
            return response('Invalid signature', 401);
        }

        $event = $request->input('event');

        if ($event !== 'charge.success') {
            return response('Ignored', 200);
        }

        $data = $request->input('data');
        $reference = $data['reference'];

        DB::transaction(function () use ($reference, $data) {
            $payment = BookingPayment::where('reference', $reference)->lockForUpdate()->first();

            if (! $payment || $payment->status === 'success') {
                return; // idempotency
            }

            $payment->update([
                'status' => 'success',
                'payment_method' => $data['channel'],
                'payment_date' => now(),
                'response' => $data,
            ]);

            $booking = $payment->booking;

            $booking->update([
                'status' => BookingStatus::APPROVED,
                'paid_at' => now(),
            ]);

            // Fire payment received event
            event(new PaymentReceived($payment));
        });

        return response('OK', 200);
    }
}

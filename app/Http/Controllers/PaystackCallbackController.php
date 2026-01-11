<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\BookingPayment;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PaystackCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $reference = $request->query('reference');

        abort_unless($reference, 400, 'Missing payment reference');

        /** @var BookingPayment $payment */
        $payment = BookingPayment::where('reference', $reference)->firstOrFail();

        // 🔐 Idempotency: if already processed, just redirect
        if ($payment->status === 'success') {
            return redirect('/guest/bookings')
                ->with('success', 'Payment already processed.');
        }

        // 🔍 Verify transaction with Paystack
        $response = Http::withToken(config('services.paystack.secret'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}")
            ->throw()
            ->json();

        $data = $response['data'] ?? null;

        if (! $data || ($data['status'] ?? null) !== 'success') {
            $payment->update([
                'status' => 'failed',
                'response' => $data,
            ]);

            return redirect('/guest/bookings')
                ->with('error', 'Payment failed or was cancelled.');
        }

        //Mark payment successful
        $payment->update([
            'status' => 'success',
            'response' => $data,
        ]);

        $booking = $payment->booking;

        //Calculate total paid so far
        $totalPaid = $booking->payments()
            ->where('status', 'success')
            ->sum('amount');

        //Fully paid → approve booking + generate invoice (once)
        if ($totalPaid >= $booking->amount && $booking->status !== 'approved') {

            $booking->update([
                'status' => 'approved',
            ]);

            // Generate invoice (service prevents duplicates)
            $invoice = app(InvoiceService::class)
                ->generateForBooking($booking);

            // Send invoice email
            Mail::to($booking->guest_email)->send(
                new InvoiceMail($invoice)
            );
        }

        return redirect('/guest/bookings')
            ->with('success', 'Payment successful.');
    }
}

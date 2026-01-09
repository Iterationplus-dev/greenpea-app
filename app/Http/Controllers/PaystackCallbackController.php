<?php

namespace App\Http\Controllers;

use App\Models\BookingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaystackCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $reference = $request->query('reference');

        abort_unless($reference, 400, 'Missing payment reference');

        $payment = BookingPayment::where('reference', $reference)->firstOrFail();

        $response = Http::withToken(config('services.paystack.secret'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}")
            ->throw()
            ->json();

        $data = $response['data'];

        if (($data['status'] ?? null) !== 'success') {
            $payment->update([
                'status' => 'failed',
                'response' => $data,
            ]);

            return redirect('/guest/bookings')
                ->with('error', 'Payment failed or was cancelled.');
        }

        $payment->update([
            'status' => 'success',
            'response' => $data,
        ]);

        $booking = $payment->booking;

        $totalPaid = $booking->payments()
            ->where('status', 'success')
            ->sum('amount');

        if ($totalPaid >= $booking->amount) {
            $booking->update(['status' => 'paid']);
        }

        if ($booking->isFullyPaid()) {
            $booking->update(['status' => 'approved']);
        }

        return redirect('/guest/bookings')
            ->with('success', 'Payment successful.');
    }
}

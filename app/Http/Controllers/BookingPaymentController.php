<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\PaystackService;

class BookingPaymentController extends Controller
{
    //
    public function pay(Request $request, Booking $booking, PaystackService $paystack)
    {
        abort_if($booking->status !== 'pending', 403);

        // Full payment for now (partial later)
        // $amountToPay = $booking->amount;

        $amount = (float) $request->query('amount', $booking->balanceAmount());

        if ($amount <= 0 || $amount > $booking->balanceAmount()) {
            abort(422, 'Invalid payment amount');
        }

        $payment = $paystack->initializePayment(
            booking: $booking,
            amount: $amount
        );

        return redirect()->away(
            $payment->response['authorization_url']
        );
    }
}

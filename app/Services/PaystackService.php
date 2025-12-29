<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\BookingPayment;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    public function __construct()
    {

    }
    public function createBookingPayment(Booking $booking, Invoice $invoice)
    {
        $response = Http::withToken(config('services.paystack.secret'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $booking->user->email,
                'amount' => (int) ($invoice->total_amount * 100),
                'reference' => $invoice->reference,
                'callback_url' => route('paystack.callback'),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'invoice_id' => $invoice->id,
                ],
            ])
            ->throw()
            ->json();

        $data = $response['data'];

        return BookingPayment::create([
            'booking_id' => $booking->id,
            'amount' => $invoice->total_amount,
            'gateway' => 'paystack',
            'reference' => $invoice->reference,
            'status' => 'pending',
            'is_installment' => false,
            'response' => $data, // includes authorization_url
        ]);
    }
}

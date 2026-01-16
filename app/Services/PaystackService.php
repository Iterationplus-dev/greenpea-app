<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Models\BookingPayment;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    public function __construct() {}

    /**
     * Initialize a Paystack payment
     *
     * @param Booking $booking
     * @param float $amount
     * @param Invoice|null $invoice
     */
    public function initializePayment(
        Booking $booking,
        float $amount,
        ?Invoice $invoice = null
    ): BookingPayment {

        $reference = $invoice?->reference ?? 'BK_' . Str::uuid();

        // $email =
        //     $booking->guest_email
        //     ?? $booking->user?->email
        //     ?? throw new \Exception('No email available for payment');

        $email = $booking->guest_email
            ?: $booking->user?->email;

        // dd($booking->user);

        if (! $email) {
            throw new \RuntimeException(
                "Cannot initialize payment: booking {$booking->id} has no email"
            );
        }


        $response = Http::withToken(config('services.paystack.secret'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $email,
                'amount' => (int) ($amount * 100),
                'reference' => $reference,
                'callback_url' => route('paystack.callback'),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'invoice_id' => $invoice?->id,
                ],
            ])
            ->throw()
            ->json();

        return BookingPayment::create([
            'booking_id'     => $booking->id,
            'amount'         => $amount,
            'gateway'        => 'paystack',
            'reference'      => $reference,
            'status'         => 'pending',
            'is_installment' => $invoice === null,
            'response'       => $response['data'], // contains authorization_url
        ]);
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

    // Wallet
    /**
     * Initialize a Paystack wallet funding
     */

    public function initializeWalletFunding(float $amount, int $userId): array
    {
        $wallet = Wallet::firstOrCreate([
            'user_id' => $userId,
        ]);

        $reference = walletReference();

        $transaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'type' => 'credit',
            'reference' => $reference,
            'description' => 'Wallet funding via Paystack',
        ]);

        $email = $wallet->user->email;

        $response = Http::withToken(config('services.paystack.secret'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $email,
                'amount' => (int) ($amount * 100),
                'reference' => $reference,
                'callback_url' => route('wallet.paystack.callback'),
                'metadata' => [
                    'wallet_id' => $wallet->id,
                    'wallet_transaction_id' => $transaction->id,
                    'type' => 'wallet',
                ],
            ])
            ->throw()
            ->json();

        return [
            'transaction' => $transaction,
            'authorization_url' => $response['data']['authorization_url'],
        ];
    }


    /**
     * Verify any Paystack transaction (Booking or Wallet)
     */
    public function verify(string $reference): array
    {
        return Http::withToken(config('services.paystack.secret'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}")
            ->throw()
            ->json('data');
    }
}

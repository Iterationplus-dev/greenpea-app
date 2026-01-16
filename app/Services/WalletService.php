<?php

namespace App\Services;

use App\Models\User;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Enums\WalletTransType;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /* ---------------------------------
     | BASIC WALLET OPERATIONS
     |---------------------------------*/

    public static function credit(User $user, float $amount, string $desc = '')
    {
        $wallet = $user->wallet()->firstOrCreate([]);

        // $wallet->increment('balance', $amount);

        $wallet->transactions()->create([
            'amount' => $amount,
            'type' => WalletTransType::CREDIT->value,
            'description' => $desc,
        ]);
    }

    public static function debit(User $user, float $amount, string $desc = '')
    {
        $wallet = $user->wallet;

        throw_if($wallet->balance < $amount, 'Insufficient balance');

        // $wallet->decrement('balance', $amount);

        $wallet->transactions()->create([
            'amount' => $amount,
            'type' => WalletTransType::DEBIT->value,
            'description' => $desc,
        ]);
    }

    /* ---------------------------------
     | BOOKING WALLET PAYMENT (NEW)
     |---------------------------------*/

    public static function payBookingFromWallet(
        Booking $booking,
        float $amount,
        User $user
    ): BookingPayment {
        if ($amount <= 0 || $amount > $booking->balanceAmount()) {
            throw new \InvalidArgumentException('Invalid payment amount.');
        }

        return DB::transaction(function () use ($booking, $amount, $user) {

            //Debit wallet
            self::debit(
                $user,
                $amount,
                "Wallet payment for booking #{$booking->id}"
            );

            //Record booking payment
            $payment = BookingPayment::create([
                'booking_id' => $booking->id,
                'amount' => $amount,
                'gateway' => 'wallet',
                'status' => 'success',
                'is_installment' => true,
                'response' => null,
            ]);

            //Auto-approve if fully paid
            if ($booking->isFullyPaid()) {
                $booking->update(['status' => 'approved']);
            }

            return $payment;
        });
    }

    /* ---------------------------------
     | OWNER PAYOUT LOGIC
     |---------------------------------*/

    public function creditOwnerForBooking(Booking $booking): void
    {
        $owner = $booking->apartment->property->owner;
        $wallet = $owner->wallet;

        $platformFeePercent = config('platform.fee_percent', 10);
        $gross = $booking->amount;

        $platformFee = round($gross * ($platformFeePercent / 100), 2);
        $net = $gross - $platformFee;

        // Credit owner
        $wallet->increment('balance', $net);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => WalletTransType::CREDIT->value,
            'amount' => $net,
            'description' => 'Booking payout',
        ]);

        // Platform revenue
        WalletTransaction::create([
            'wallet_id' => null,
            'type' => 'platform_fee',
            'amount' => $platformFee,
            'description' => 'Platform fee from booking',
        ]);
    }
}

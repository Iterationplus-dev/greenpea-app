<?php

namespace App\Services;

use App\Models\User;
use App\Models\Booking;
use App\Enums\PaymentStatus;
use App\Enums\WalletTransType;
use App\Models\BookingPayment;
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

        $wallet->transactions()->create([
            'amount' => $amount,
            'type' => WalletTransType::CREDIT->value,
            'description' => $desc,
        ]);
    }

    public static function debit(User $user, float $amount, string $desc = '')
    {
        return DB::transaction(function () use ($user, $amount, $desc) {

            $wallet = $user->wallet()->lockForUpdate()->firstOrCreate([]);

            if ($wallet->balance < $amount) {
                throw new \RuntimeException('Insufficient balance');
            }

            return $wallet->transactions()->create([
                'amount' => $amount,
                'type' => WalletTransType::DEBIT->value,
                'description' => $desc,
            ]);
        });
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
                'status' => PaymentStatus::SUCCESS->value,
                'is_installment' => true,
                'response' => null,
            ]);

            //Auto-approve if fully paid
            // if ($booking->isFullyPaid()) {
            //     $booking->update(['status' => 'approved']);
            // }

            return $payment;
        });
    }

    /* ---------------------------------
     | OWNER PAYOUT LOGIC
     |---------------------------------*/

    public function creditOwnerForBooking(Booking $booking): void
    {
        $owner = $booking->apartment?->property?->owner;

        if (! $owner) {
            logger()->error('Cannot credit owner: owner not found', [
                'booking_id' => $booking->id,
            ]);
            return;
        }

        // Ensure wallet exists
        $wallet = $owner->wallet()->firstOrCreate([]);

        $gross = $booking->net_amount ?? $booking->amount;

        $platformFee = platformFee($gross);
        $net = $gross - $platformFee;

        $alreadyCredited = WalletTransaction::where('description', "Booking payout: {$booking->reference}")
            ->exists();
        if ($alreadyCredited) {
            return;
        }

        // Credit owner via transaction (NOT increment)
        $wallet->transactions()->create([
            'amount' => $net,
            'type' => WalletTransType::CREDIT->value,
            'description' => "Booking payout: {$booking->reference}",
        ]);

        // Credit platform wallet
        $platformUser = User::where('email', 'platform@system.local')->first();

        if ($platformUser) {
            $platformWallet = $platformUser->wallet()->firstOrCreate([]);

            $platformWallet->transactions()->create([
                'amount' => $platformFee,
                'type' => WalletTransType::CREDIT->value,
                'description' => "Platform fee from booking {$booking->reference}",
            ]);
        }
    }
}

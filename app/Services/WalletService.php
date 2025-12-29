<?php

namespace App\Services;

use App\Models\User;
use App\Models\Booking;
use App\Enums\WalletTransType;
use App\Models\WalletTransaction;

class WalletService
{
    public static function credit(User $user, float $amount, string $desc = '')
    {
        $wallet = $user->wallet()->firstOrCreate([]);

        $wallet->increment('balance', $amount);

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

        $wallet->decrement('balance', $amount);

        $wallet->transactions()->create([
            'amount' => $amount,
            'type' => WalletTransType::DEBIT->value,
            'description' => $desc,
        ]);
    }

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
            'type' => 'credit',
            'amount' => $net,
            'description' => 'Booking payout: ' . $booking->reference,
        ]);

        // Platform revenue (optional wallet)
        WalletTransaction::create([
            'wallet_id' => null,
            'type' => 'platform_fee',
            'amount' => $platformFee,
            'description' => 'Platform fee from booking ' . $booking->reference,
        ]);
    }
}

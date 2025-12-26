<?php

namespace App\Services;

use App\Enums\WalletTransType;
use App\Models\User;

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
}

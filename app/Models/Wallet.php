<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $table = 'wallets';
    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
    //
    public function credit(float $amount, string $description = null): void
    {
        $this->increment('balance', $amount);

        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description,
        ]);
    }

    public function debit(float $amount, string $description = null): void
    {
        if ($amount > $this->balance) {
            throw new \RuntimeException('Insufficient wallet balance');
        }

        $this->decrement('balance', $amount);

        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'description' => $description,
        ]);
    }
}

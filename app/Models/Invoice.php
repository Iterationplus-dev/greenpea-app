<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $table = 'invoices';
    protected $fillable = [
        'booking_id',
        'number',
        'amount',
        'platform_fee',
        'net_amount',

        'amount_paid',
        'balance_due',

        'pdf_path',
        'issued_at',
        'paid_at',
        'status',
        'pdf_public_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'issued_at' => 'datetime',
        'paid_at'   => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

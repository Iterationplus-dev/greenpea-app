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
        'pdf_path',
        'issued_at',
        'pdf_public_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'issued_at' => 'datetime',
        'paid_at'   => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

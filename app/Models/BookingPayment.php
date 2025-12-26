<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    //
    protected $table = 'booking_payments';
    protected $fillable = [
        'booking_id',
        'amount',
        'gateway',
        'reference',
        'payment_method',
        'payment_date',
        'status',
        'is_installment',
        'response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_installment' => 'boolean',
        'response' => 'array',
    ];

     /*  RELATIONSHIPS */

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

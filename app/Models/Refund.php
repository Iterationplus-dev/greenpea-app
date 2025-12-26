<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    //
    protected $table = 'refunds';
    protected $fillable = [
        'booking_id',
        'amount',
        'status',
        'paystack_refund_reference',
        'reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /* RELATIONSHIPS  */

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

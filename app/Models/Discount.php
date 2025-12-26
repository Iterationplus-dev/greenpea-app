<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    /* RELATIONSHIPS */

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

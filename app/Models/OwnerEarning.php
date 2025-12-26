<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;

class OwnerEarning extends Model
{
    //
    protected $table = 'owner_earnings';
    protected $fillable = [
        'owner_id',
        'booking_id',
        'gross_amount',
        'platform_fee',
        'net_amount',
        'earned_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'earned_at' => 'date',
    ];

    /* RELATIONSHIPS */

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }


    protected static function booted()
    {
        static::addGlobalScope(function ($query) {
            if (auth()->user()?->role === UserRole::PROPERTY_OWNER) {
                $query->where('owner_id', auth()->id());
            }
        });
    }
}

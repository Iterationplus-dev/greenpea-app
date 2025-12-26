<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'usage_limit',
        'used_count',
        'minimum_amount',
        'is_active',
        'expires_at',
        'property_id',
    ];

     protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

     /* ================= RELATIONSHIPS ================= */

    public function redemptions()
    {
        return $this->hasMany(CouponRedemption::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /* ================= SCOPES ================= */

    public function scopeActive(Builder $query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /* ================= HELPERS ================= */

    public function isExhausted(): bool
    {
        return $this->usage_limit !== null
            && $this->used_count >= $this->usage_limit;
    }
}

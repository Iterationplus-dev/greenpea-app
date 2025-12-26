<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Booking;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public static function apply(string $code, Booking $booking): float
    {
        $coupon = Coupon::active()
            ->where('code', $code)
            ->first();

        if (! $coupon) {
            throw ValidationException::withMessages([
                'coupon' => 'Invalid or expired coupon.',
            ]);
        }

        if ($coupon->isExhausted()) {
            throw ValidationException::withMessages([
                'coupon' => 'Coupon usage limit reached.',
            ]);
        }

        if ($coupon->property_id
            && $coupon->property_id !== $booking->apartment->property_id) {
            throw ValidationException::withMessages([
                'coupon' => 'Coupon not valid for this property.',
            ]);
        }

        if ($coupon->minimum_amount
            && $booking->total_amount < $coupon->minimum_amount) {
            throw ValidationException::withMessages([
                'coupon' => 'Booking amount too low for this coupon.',
            ]);
        }

        return self::calculateDiscount($coupon, $booking->total_amount);
    }

    protected static function calculateDiscount(Coupon $coupon, float $amount): float
    {
        return match ($coupon->discount_type) {
            'percentage' => round(
                ($coupon->discount_value / 100) * $amount,
                2
            ),
            'fixed' => min($coupon->discount_value, $amount),
        };
    }
}

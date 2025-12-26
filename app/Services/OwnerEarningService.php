<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\OwnerEarning;

class OwnerEarningService
{
    public static function record(Booking $booking)
    {
        $percentage = setting('platform_fee_percentage', 10);

        $fee = bcmul($booking->net_amount, $percentage / 100, 2);
        $net = bcsub($booking->net_amount, $fee, 2);

        OwnerEarning::create([
            'owner_id' => $booking->apartment->property->owner_id,
            'booking_id' => $booking->id,
            'gross_amount' => $booking->net_amount,
            'platform_fee' => $fee,
            'net_amount' => $net,
            'earned_at' => now(),
        ]);
    }
}

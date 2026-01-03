<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class RefundService
{
    public function refundBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {

            if ($booking->status === 'refunded') {
                return;
            }

            $owner = $booking->apartment->property->owner;
            $ownerWallet = $owner->wallet;
            $guestWallet = $booking->user->wallet;

            $gross = $booking->amount;
            $platformFeePercent = config('platform.fee_percent', 10);
            $platformFee = round($gross * ($platformFeePercent / 100), 2);
            $ownerNet = $gross - $platformFee;

            // 1. Mark booking
            $booking->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            // 2. Guest gets full refund
            $guestWallet->increment('balance', $gross);

            // 3. Owner loses their payout
            $ownerWallet->decrement('balance', $ownerNet);

            // 4. Release apartment
            $booking->apartment->update([
                'is_available' => true,
            ]);

            // 5. Mark payments
            $booking->payments()->update([
                'status' => 'refunded',
            ]);

            // 6. Log everything
            activity('booking')
                ->performedOn($booking)
                ->causedBy(auth()->user())
                ->log('Booking refunded ₦' . number_format($gross, 2));
        });
    }
}

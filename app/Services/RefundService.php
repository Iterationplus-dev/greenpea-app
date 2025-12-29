<?php
namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class RefundService
{
    public function refundBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {

            // 1. Mark booking
            $booking->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            // 2. Refund wallet
            $wallet = $booking->user->wallet;
            $wallet->increment('balance', $booking->total_amount);

            // 3. Mark payments
            $booking->payments()->update([
                'status' => 'refunded',
            ]);

            // 4. Log activity
            activity('booking')
                ->performedOn($booking)
                ->causedBy(auth()->user())
                ->log('Booking refunded');
        });
    }
}

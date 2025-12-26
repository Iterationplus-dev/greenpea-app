<?php

namespace App\Observers;

use App\Enums\PaymentStatus;
use App\Models\Booking;
use OwnerEarningService;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        if (
            $booking->isDirty('status') &&
            $booking->status === PaymentStatus::PAID &&
            !$booking->ownerEarning
        ) {
            OwnerEarningService::record($booking);
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}

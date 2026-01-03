<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use App\Models\Admin;

class BookingPolicy
{
    /**
     * Can list bookings
     */
    public function viewAny($actor): bool
    {
        // Admin panel
        if ($actor instanceof Admin) {
            return $actor->canManageBookings();
        }

        // Frontend users (they will be scoped to their own bookings)
        if ($actor instanceof User) {
            return true;
        }

        return false;
    }

    /**
     * Can view a booking
     */
    public function view($actor, Booking $booking): bool
    {
        if ($actor instanceof Admin) {
            return $actor->canManageBookings();
        }

        if ($actor instanceof User) {
            return
                $booking->user_id === $actor->id || // guest
                $booking->apartment->property->owner_id === $actor->id; // owner
        }

        return false;
    }

    /**
     * Only admins can update bookings (approve / cancel)
     */
    public function update($actor, Booking $booking): bool
    {
        if ($actor instanceof Admin) {
            return $actor->canManageBookings();
        }

        return false;
    }

    /**
     * Only finance admins can refund
     */
    public function refund($actor, Booking $booking): bool
    {
        if ($actor instanceof Admin) {
            return $actor->canManageFinance();
        }

        return false;
    }

    /**
     * Nobody deletes bookings (accounting safety)
     */
    public function delete($actor, Booking $booking): bool
    {
        return false;
    }

    public function restore($actor, Booking $booking): bool
    {
        return false;
    }

    public function forceDelete($actor, Booking $booking): bool
    {
        return false;
    }
}

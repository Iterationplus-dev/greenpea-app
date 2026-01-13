<?php

namespace App\Enums;

enum GroupLabel: string
{
    case FACILITYMGT = 'Facility Management';
    case BOOKINGS = 'Manage Bookings';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::FACILITYMGT => 'Facility-Management',
            self::BOOKINGS => 'Manage Bookings',
            self::PARTIALLY_PAID => 'Partially Paid',
            self::PAID => 'Paid',
            self::CANCELLED => 'Cancelled',
        };
    }
}

<?php

namespace App\Enums;

enum GroupLabel: string
{
    case FACILITYMGT = 'Facility Management';
    case BOOKINGS = 'Manage Bookings';
    case FINANCE = 'Finance';
    case SETTINGS = 'Settings';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::FACILITYMGT => 'Facility-Management',
            self::BOOKINGS => 'Manage Bookings',
            self::FINANCE => 'Finance',
            self::SETTINGS => 'Settings',
            self::CANCELLED => 'Cancelled',
        };
    }
}

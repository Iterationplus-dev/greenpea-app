<?php

namespace App\Enums;

enum GroupLabel: string
{
    case FACILITYMGT = 'Property Management';
    case BOOKINGS = 'Manage Bookings';
    case FINANCE = 'Finance';

    case ACCOUNT = 'Account Management';

    case SETTINGS = 'Settings';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::FACILITYMGT => 'Property-Management',
            self::BOOKINGS => 'Manage Bookings',
            self::FINANCE => 'Finance',
            self::ACCOUNT => 'Account Management',
            self::SETTINGS => 'Settings',
            self::CANCELLED => 'Cancelled',
        };
    }
}

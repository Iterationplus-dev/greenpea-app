<?php

namespace App\Enums;

enum BookingStatus: string
{
     case PENDING   = 'pending';
    case APPROVED  = 'approved';
    case PAID      = 'paid';
    case CANCELLED = 'cancelled';
    case REFUNDED  = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::PAID => 'Paid',
            self::REFUNDED => 'Refunded',
            self::CANCELLED => 'Cancelled',
        };
    }
}

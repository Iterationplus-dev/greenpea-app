<?php

namespace App\Enums;

enum PaymentStatus:string
{
    //
    case PENDING = 'pending';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PARTIALLY_PAID => 'Partially Paid',
            self::PAID => 'Paid',
            self::CANCELLED => 'Cancelled',
        };
    }
}

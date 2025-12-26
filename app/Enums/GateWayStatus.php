<?php

namespace App\Enums;

enum GateWayStatus: string
{
    //
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case PENDING = 'pending';
    public function label(): string
    {
        return match ($this) {
            self::SUCCESS => 'Success',
            self::FAILED => 'Failed',
            self::PENDING => 'Pending',
        };
    }
}

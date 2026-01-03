<?php

namespace App\Enums;

enum AdminRole: string
{
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super-admin';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'admin',
            self::SUPER_ADMIN => 'super-admin',
        };
    }
}

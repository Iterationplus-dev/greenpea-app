<?php

namespace App\Enums;

enum AdminType: string {
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case STAFF = 'staff';
    case OWNER = 'owner';
    case CEO = 'CEO';
    case CONSULTANT = 'consultant';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'admin',
            self::MANAGER => 'manager',
            self::STAFF => 'staff',
            self::OWNER => 'owner',
            self::CEO => 'CEO',
            self::CONSULTANT => 'consultant',
        };
    }
}


<?php

namespace App\Enums;

enum UserStatus:string
{
    //
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case BANNED = 'banned';
    case PENDING = 'pending';

    public function label(): string
    {
        return match($this) {
            UserStatus::ACTIVE => 'Active',
            UserStatus::INACTIVE => 'Inactive',
            UserStatus::SUSPENDED => 'Suspended',
            UserStatus::BANNED => 'Banned',
            UserStatus::PENDING => 'Pending',
        };
    }
}

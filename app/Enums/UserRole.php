<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case PROPERTY_OWNER = 'property_owner';
    case SUPPORT = 'support';
    case CUSTOMER = 'customer';
    case GUEST = 'guest';
    case AGENT = 'agent';
    case VENDOR = 'vendor';
    case STAFF = 'staff';
    case MARKETER= 'marketer';
    case ACCOUNTANT = 'accountant';
    case DEVELOPER = 'developer';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::MANAGER => 'Manager',
            self::PROPERTY_OWNER => 'Property Owner',
            self::SUPPORT => 'Support',
            self::CUSTOMER => 'Customer',
            self::GUEST => 'Guest',
            self::AGENT => 'Agent',
            self::VENDOR => 'Vendor',
            self::STAFF => 'Staff',
            self::MARKETER => 'Marketer',
            self::ACCOUNTANT => 'Accountant',
            self::DEVELOPER => 'Developer',
        };
    }

}

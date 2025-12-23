<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case PROPERTY_OWNER = 'property_owner';
    case SUPPORT = 'support';
    case CUSTOMER = 'customer';
    case AGENT = 'agent';
    case VENDOR = 'vendor';
    case STAFF = 'staff';
    case MARKETING = 'marketing';
    case ACCOUNTANT = 'accountant';
    case DEVELOPER = 'developer';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::PROPERTY_OWNER => 'Property Owner',
            self::SUPPORT => 'Support',
            self::CUSTOMER => 'Customer',
            self::AGENT => 'Agent',
            self::VENDOR => 'Vendor',
            self::STAFF => 'Staff',
            self::MARKETING => 'Marketing',
            self::ACCOUNTANT => 'Accountant',
            self::DEVELOPER => 'Developer',
        };
    }

}

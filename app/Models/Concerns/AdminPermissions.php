<?php

namespace App\Models\Concerns;

use App\Enums\AdminRole;
use App\Enums\AdminType;

trait AdminPermissions
{
    public function isSuper(): bool
    {
        return $this->role === AdminRole::SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === AdminRole::ADMIN;
    }

    public function canManageBookings(): bool
    {
        return in_array($this->type, [
            AdminType::CEO,
            AdminType::MANAGER,
            AdminType::STAFF,
        ]);
    }

    public function canManageFinance(): bool
    {
        return in_array($this->type, [
            AdminType::CEO,
            AdminType::MANAGER,
        ]);
    }

    public function canManageProperties(): bool
    {
        return in_array($this->type, [
            AdminType::CEO,
            AdminType::OWNER,
            AdminType::MANAGER,
        ]);
    }
}



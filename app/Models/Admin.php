<?php

namespace App\Models;

use Filament\Panel;
use App\Enums\AdminRole;
use App\Enums\AdminType;
use App\Enums\AdminStatus;
use App\Models\Concerns\AdminPermissions;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements FilamentUser
{
    //
    use AdminPermissions;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'type',
        'status',
        'email_verified_at',
    ];

    protected $hidden = ['password'];
    //

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => AdminRole::class,
        'type' => AdminType::class,
        'status' => AdminStatus::class,
        'compact_tables' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'app';
    }


    //
}

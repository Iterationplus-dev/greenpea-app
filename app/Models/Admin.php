<?php

namespace App\Models;

use App\Enums\AdminRole;
use App\Enums\AdminType;
use App\Enums\AdminStatus;
use App\Models\Concerns\AdminPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
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
    ];
    //
}

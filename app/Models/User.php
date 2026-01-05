<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'role',
                'status'
            ])
            ->logOnlyDirty()
            ->useLogName('user')
            ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    // protected $casts = [
    //     'role' => UserRole::class,
    //     'status' => UserStatus::class,
    // ];

    /*ROLE HELPERS*/
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isOwner(): bool
    {
        return $this->role === UserRole::PROPERTY_OWNER;
    }

    public function hasRole(UserRole|string $role): bool
    {
        // return $this->role === $role;
        return $this->role === $role || $this->role?->value === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        // return in_array($this->role?->value, $roles, true);
        return in_array($this->role->value ?? $this->role, $roles, true);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // return in_array($this->role, [
        //     UserRole::SUPER_ADMIN,
        //     UserRole::SUPPORT,
        // ], true);

        return match ($panel->getId()) {
            // Guest / User panel
            'guest' => true,

            // Admin panel (existing logic preserved)
            'app' => in_array($this->role, [
                UserRole::SUPER_ADMIN,
                UserRole::SUPPORT,
            ], true),

            default => false,
        };
    }


    // Guards
    // public static function canViewAny(): bool
    // {
    //     return auth()->guard('admin')->check();
    // }




    /* RELATIONSHIPS */

    // Owner → Properties
    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }
    // Guest → Bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Owner → Earnings
    public function ownerEarnings()
    {
        return $this->hasMany(OwnerEarning::class, 'owner_id');
    }
}

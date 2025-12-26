<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    //
    use HasFactory;
    protected $table = 'properties';
    protected $fillable = [
        'owner_id',
        'name',
        'city',
        'address',
        'description',
        'slug',
        'is_active',
    ];

    /* RELATIONSHIPS */

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Apartment::class);
    }

    public function isAdmin(): bool
    {
        // Assuming there's a method to get the currently authenticated user
        // $user = auth()->user();
        // return $user && $user->id === $this->owner_id;
        return $this->role === UserRole::SUPER_ADMIN;
    }
}

<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Apartment extends Model
{
    //
    protected $table = 'apartments';

    protected $fillable = [
        'property_id',
        'name',
        'slug',
        'description',
        'daily_price',
        'unit_number',
        'floor',
        'bedrooms',
        'bathrooms',
        'square_feet',
        'is_available',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Apartment $apartment) {
            if (empty($apartment->slug)) {
                $apartment->slug = static::generateUniqueSlug($apartment->name);
            }
        });

        static::updating(function (Apartment $apartment) {
            if ($apartment->isDirty('name') && ! $apartment->isDirty('slug')) {
                $apartment->slug = static::generateUniqueSlug($apartment->name, $apartment->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original.'-'.$count++;
        }

        return $slug;
    }

    protected $casts = [
        'daily_price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /* RELATIONSHIPS */

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function images()
    {
        return $this->hasMany(ApartmentImage::class);
    }

    public function featuredImage()
    {
        return $this->hasOne(ApartmentImage::class)
            ->where('is_featured', true);
    }

    // Fallback: if no featured, use first image
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featuredImage?->url;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'apartment_amenity');
    }

    public function scopeAvailableBetween($query, $start, $end)
    {
        return $query->whereDoesntHave('bookings', function ($q) use ($start, $end) {
            $q->where('status', '!=', 'cancelled')
                ->where(function ($overlap) use ($start, $end) {
                    $overlap->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($q) use ($start, $end) {
                            $q->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                        });
                });
        });
    }

    public function scopeAvailableForDates(
        Builder $query,
        string $start,
        string $end
    ): Builder {
        return $query->whereDoesntHave('bookings', function ($q) use ($start, $end) {
            $q->whereNotIn('status', [BookingStatus::CANCELLED])
                ->where(function ($overlap) use ($start, $end) {
                    $overlap
                        ->where('start_date', '<', $end)
                        ->where('end_date', '>', $start);
                });
        });
    }
}

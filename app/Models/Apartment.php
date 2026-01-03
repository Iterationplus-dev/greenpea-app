<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    //
    protected $table = 'apartments';
    protected $fillable = [
        'property_id',
        'name',
        'description',
        'monthly_price',
        'unit_number',
        'floor',
        'bedrooms',
        'bathrooms',
        'square_feet',
        'is_available',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
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
    public function getFeaturedImageAttribute()
    {
        return $this->featuredImage()->first() ?? $this->images()->orderBy('sort_order')->first();
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image?->url;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
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
}

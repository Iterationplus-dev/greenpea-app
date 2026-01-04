<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ApartmentImage extends Model
{
    //
    protected $table = 'apartment_images';
    protected $fillable = [
        'apartment_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // public function getUrlAttribute()
    // {
    //     return Cloudinary::getUrl($this->image_path, [
    //         'quality' => 'auto',
    //         'fetch_format' => 'auto',
    //         'width' => 1200,
    //         'crop' => 'fill',
    //     ]);
    // }

    public function getUrlAttribute(): string
    {
        return Cloudinary::image($this->image_path)
            ->resize(\Cloudinary\Transformation\Resize::fill(1200, 800))
            ->toUrl();
    }


    public static function booted()
    {
        static::creating(function ($image) {
            if (! static::where('apartment_id', $image->apartment_id)->exists()) {
                $image->is_featured = true;
            }
        });

        static::saving(function ($image) {
            if ($image->is_featured) {
                static::where('apartment_id', $image->apartment_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_featured' => false]);
            }
        });
    }


    /* RELATIONSHIPS */

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}

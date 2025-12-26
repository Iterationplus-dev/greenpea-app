<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    /* RELATIONSHIPS */

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}

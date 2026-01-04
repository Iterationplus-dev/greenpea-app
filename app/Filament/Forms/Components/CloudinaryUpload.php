<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\ApartmentImage;

class CloudinaryUpload extends Field
{
    protected string $view = 'filament.components.cloudinary-upload';
}

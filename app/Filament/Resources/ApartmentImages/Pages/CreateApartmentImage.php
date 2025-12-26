<?php

namespace App\Filament\Resources\ApartmentImages\Pages;

use App\Filament\Resources\ApartmentImages\ApartmentImageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApartmentImage extends CreateRecord
{
    protected static string $resource = ApartmentImageResource::class;
}

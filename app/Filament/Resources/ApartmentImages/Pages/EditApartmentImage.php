<?php

namespace App\Filament\Resources\ApartmentImages\Pages;

use App\Filament\Resources\ApartmentImages\ApartmentImageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApartmentImage extends EditRecord
{
    protected static string $resource = ApartmentImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

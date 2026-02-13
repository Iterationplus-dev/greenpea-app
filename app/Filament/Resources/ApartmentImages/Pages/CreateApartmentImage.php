<?php

namespace App\Filament\Resources\ApartmentImages\Pages;

use App\Filament\Resources\ApartmentImages\ApartmentImageResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateApartmentImage extends CreateRecord
{
    protected static string $resource = ApartmentImageResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Image Added')
            ->body('The apartment image has been uploaded successfully.')
            ->success();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

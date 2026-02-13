<?php

namespace App\Filament\Resources\ApartmentImages\Pages;

use App\Filament\Resources\ApartmentImages\ApartmentImageResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditApartmentImage extends EditRecord
{
    protected static string $resource = ApartmentImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->size('sm')
                ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Image Deleted')
                        ->body('The apartment image has been deleted successfully.')
                ),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Image Updated')
            ->body('The apartment image details have been updated successfully.')
            ->success();
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}

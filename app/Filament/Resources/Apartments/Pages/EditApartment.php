<?php

namespace App\Filament\Resources\Apartments\Pages;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Apartments\ApartmentResource;

class EditApartment extends EditRecord
{
    protected static string $resource = ApartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->size('sm')
                ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Apartment Deleted')
                        ->body('The apartment details deleted successfully')
                ),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        // return $this->getResource()::getUrl('index');
        return $this->getResource()::getUrl('index', ['record' => $this->record->id]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title("Apartment Updated")
            ->body('Apartment details updated successful')
            ->success();
    }
}

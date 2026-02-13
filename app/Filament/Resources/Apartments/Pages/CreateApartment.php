<?php

namespace App\Filament\Resources\Apartments\Pages;

use App\Filament\Resources\Apartments\ApartmentResource;
use App\Services\ApartmentImageService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateApartment extends CreateRecord
{
    protected static string $resource = ApartmentResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Create Apartment');
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['slug'] = Str::slug($data['name']);

    //     return $data;
    // }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Apartment Created')
            ->body('New apartment add successfully')
            ->success();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $images = $data['images'] ?? [];
        unset($data['images']);

        // dd($images);
        $apartment = parent::handleRecordCreation($data);

        app(ApartmentImageService::class)
            // ->store($apartment->id, $images);
            ->storeFromPaths($apartment->id, $images);

        return $apartment;
    }
}

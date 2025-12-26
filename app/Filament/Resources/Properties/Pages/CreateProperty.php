<?php

namespace App\Filament\Resources\Properties\Pages;

use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Properties\PropertyResource;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;



    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Create Property');
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['name']);

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Product Created')
            ->body('New product add successfully')
            ->success();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('list');
    }
}

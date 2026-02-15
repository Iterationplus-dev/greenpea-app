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

        // Ensure owner_id is set for super admins
        // Remove this block if you want to allow super admins to select the owner from the form instead of defaulting to the first property owner.
        if (auth()->user()->isSuper()) {
            $firstOwner = \App\Models\User::query()
                ->where('role', \App\Enums\UserRole::PROPERTY_OWNER->value)
                ->first();
            if ($firstOwner) {
                $data['owner_id'] = $firstOwner->id;
            }
        }

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('New Property Added')
            ->body('New property added successfully')
            ->success();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['record' => $this->record->id]);
    }
}

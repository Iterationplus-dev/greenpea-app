<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        // return $this->getResource()::getUrl('index', ['tableSearch' => $this->record->email]);
        return $this->getResource()::getUrl('index', ['record' => $this->record->id]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title("Details Updated")
            ->body('User details updated successful')
            ->success();
    }
}

<?php

namespace App\Filament\Resources\Settings\Pages;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Settings\SettingResource;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        // return $this->getResource()::getUrl('index', ['tableSearch' => $this->record->email]);
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title("Settings Updated")
            ->body('General settings details updated successful')
            ->success();
    }
}

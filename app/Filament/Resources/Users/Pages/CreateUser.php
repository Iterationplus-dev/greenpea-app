<?php

namespace App\Filament\Resources\Users\Pages;

use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Users\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;



    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Create User');
    }


    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('User Created')
            ->body('New user created successfully')
            ->success();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

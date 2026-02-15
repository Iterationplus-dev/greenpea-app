<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Users\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Users List';
    }

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('User Deleted')
            ->body('User deleted successfully.')
            ->success();
    }
}

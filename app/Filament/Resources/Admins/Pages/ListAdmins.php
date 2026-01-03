<?php

namespace App\Filament\Resources\Admins\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Admins\AdminResource;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];


    }

     public function getTitle(): string
    {
        return 'Admin Users';
    }

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Admin user Deleted')
            ->body('Admin user deleted successfully.')
            ->success();
    }
}

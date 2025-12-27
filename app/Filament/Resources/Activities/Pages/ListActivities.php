<?php

namespace App\Filament\Resources\Activities\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Activities\ActivityResource;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Activity Logs';
    }

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Activity Log Deleted')
            ->body('Activity log deleted successfully.')
            ->success();
    }
}

<?php

namespace App\Filament\Resources\Apartments\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Apartments\ApartmentResource;

class ListApartments extends ListRecords
{
    protected static string $resource = ApartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Apartment')
                ->size('sm')
                ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Available Apartments';
    }



    protected function getBulkDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Apartments Deleted')
            ->body('Selected apartments were deleted.')
            ->success();
    }
}

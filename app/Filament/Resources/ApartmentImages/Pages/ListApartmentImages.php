<?php

namespace App\Filament\Resources\ApartmentImages\Pages;

use App\Filament\Resources\ApartmentImages\ApartmentImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApartmentImages extends ListRecords
{
    protected static string $resource = ApartmentImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()

                ->size('sm')
                ->extraAttributes(['class' => 'text-xs px-3 py-1.5']),
        ];
    }
}

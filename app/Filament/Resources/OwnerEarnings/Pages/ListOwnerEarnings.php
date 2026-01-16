<?php

namespace App\Filament\Resources\OwnerEarnings\Pages;

use App\Filament\Resources\OwnerEarnings\OwnerEarningResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOwnerEarnings extends ListRecords
{
    protected static string $resource = OwnerEarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Earnings";
    }
}

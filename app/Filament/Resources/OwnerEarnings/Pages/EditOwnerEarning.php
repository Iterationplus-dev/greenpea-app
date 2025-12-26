<?php

namespace App\Filament\Resources\OwnerEarnings\Pages;

use App\Filament\Resources\OwnerEarnings\OwnerEarningResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOwnerEarning extends EditRecord
{
    protected static string $resource = OwnerEarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

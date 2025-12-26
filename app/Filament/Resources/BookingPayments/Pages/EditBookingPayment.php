<?php

namespace App\Filament\Resources\BookingPayments\Pages;

use App\Filament\Resources\BookingPayments\BookingPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingPayment extends EditRecord
{
    protected static string $resource = BookingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

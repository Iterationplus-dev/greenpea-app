<?php

namespace App\Filament\Resources\BookingPayments\Pages;

use App\Filament\Resources\BookingPayments\BookingPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingPayment extends CreateRecord
{
    protected static string $resource = BookingPaymentResource::class;
}

<?php

namespace App\Filament\Guest\Resources\Bookings\Pages;

use App\Filament\Guest\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
}

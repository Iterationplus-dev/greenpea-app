<?php

namespace App\Filament\Guest\Pages;

use Filament\Pages\Page;
use App\Models\Apartment;

class ContinueBooking extends Page
{
    protected string $view = 'filament.guest.pages.continue-booking';
//   protected string $view = 'filament-panels::filament.guest.pages.create-booking';

    public ?array $intent = null;
    public ?Apartment $apartment = null;

    public function mount(): void
    {
        $this->intent = session('booking.intent');

        abort_if(! $this->intent, 404);

        $this->apartment = Apartment::findOrFail(
            $this->intent['apartment_id']
        );
    }
}

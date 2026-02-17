<?php

namespace App\Filament\Guest\Pages;

use App\Models\Apartment;
use Carbon\Carbon;
use Filament\Pages\Page;

class ContinueBooking extends Page
{
    protected string $view = 'filament.guest.pages.continue-booking';

    protected static ?string $title = 'Continue Booking';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $intent = null;

    public ?Apartment $apartment = null;

    public int $days = 1;

    public float $totalAmount = 0;

    public function mount(): void
    {
        $this->intent = session('booking.intent');

        abort_if(! $this->intent, 404);

        $this->apartment = Apartment::with(['property', 'featuredImage'])
            ->findOrFail($this->intent['apartment_id']);

        $start = Carbon::parse($this->intent['start_date']);
        $end = Carbon::parse($this->intent['end_date']);

        $this->days = max($start->diffInDays($end), 1);

        $this->totalAmount = $this->days * $this->apartment->daily_price;
    }
}

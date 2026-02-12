<?php

namespace App\Filament\Guest\Pages;

use App\Exceptions\BookingUnavailableException;
use App\Models\Apartment;
use App\Services\BookingService;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;

class CreateBooking extends Page
{
    use InteractsWithForms;

    protected string $view = 'filament.guest.pages.create-booking';

    protected static ?string $title = 'Complete Booking';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $intent = null;

    public ?Apartment $apartment = null;

    public int $months = 1;

    public float $totalAmount = 0;

    /** Form state */
    public array $data = [];

    public function mount(): void
    {
        $this->intent = session('booking.intent');

        abort_if(! $this->intent, 404);

        $this->apartment = Apartment::with(['property', 'featuredImage'])
            ->findOrFail($this->intent['apartment_id']);

        $start = Carbon::parse($this->intent['start_date']);
        $end = Carbon::parse($this->intent['end_date']);

        $this->months = max(
            ($end->year - $start->year) * 12 + ($end->month - $start->month),
            1
        );

        $this->totalAmount = $this->months * $this->apartment->monthly_price;

        $this->data = [
            'guest_name' => Auth::user()->name,
            'guest_email' => Auth::user()->email,
            'start_date' => $this->intent['start_date'],
            'end_date' => $this->intent['end_date'],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Guest Information')
                ->schema([
                    TextInput::make('guest_name')
                        ->label('Full name')
                        ->required(),

                    TextInput::make('guest_email')
                        ->label('Email address')
                        ->email()
                        ->required(),

                    DatePicker::make('start_date')
                        ->label('Check-in date')
                        ->disabled()
                        ->required(),

                    DatePicker::make('end_date')
                        ->label('Check-out date')
                        ->disabled()
                        ->required(),
                ])
                ->statePath('data'),
        ];
    }

    public function create(): void
    {
        $start = Carbon::parse($this->intent['start_date']);
        $end = Carbon::parse($this->intent['end_date']);

        $months = max(
            ($end->year - $start->year) * 12 + ($end->month - $start->month),
            1
        );

        $amount = $months * $this->apartment->monthly_price;

        try {
            app(BookingService::class)->create([
                'apartment_id' => $this->apartment->id,
                'guest_name' => $this->data['guest_name'],
                'guest_email' => $this->data['guest_email'],
                'user_id' => Auth::id(),
                'start_date' => $this->intent['start_date'],
                'end_date' => $this->intent['end_date'],
                'amount' => $amount,
            ]);
        } catch (BookingUnavailableException $e) {
            Notification::make()
                ->title('Apartment not available')
                ->body($e->getMessage())
                ->warning()
                ->send();

            return;
        }

        session()->forget('booking.intent');

        $this->redirect('/guest/bookings');
    }
}

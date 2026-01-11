<?php

namespace App\Filament\Guest\Pages;

use Filament\Pages\Page;
use App\Models\Apartment;
use Filament\Actions\Action;
use App\Services\BookingService;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use App\Exceptions\BookingUnavailableException;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateBooking extends Page
{
    use InteractsWithForms;
    protected string $view = 'filament.guest.pages.create-booking';

    public ?array $intent = null;
    public ?Apartment $apartment = null;

    /** Form state */
    public array $data = [];

    public function mount(): void
    {
        $this->intent = session('booking.intent');

        abort_if(! $this->intent, 404);

        $this->apartment = Apartment::findOrFail(
            $this->intent['apartment_id']
        );

        $this->data = [
            'guest_name'  => Auth::user()->name,
            'guest_email' => Auth::user()->email,
            'start_date'  => $this->intent['start_date'],
            'end_date'    => $this->intent['end_date'],
        ];
    }

    /**
     * Filament v4 form schema
     */
    protected function getFormSchema(): array
    {
        return [
            Section::make('Booking Details')
                ->schema([
                    TextInput::make('guest_name')
                        ->label('Full name')
                        ->required(),

                    TextInput::make('guest_email')
                        ->label('Email address')
                        ->email()
                        ->required(),

                    DatePicker::make('start_date')
                        ->disabled()
                        ->required(),

                    DatePicker::make('end_date')
                        ->disabled()
                        ->required(),
                ])
                ->statePath('data'),

        ];
    }

    /**
     * Create booking
     */
    public function create(): void
    {
        $start = \Carbon\Carbon::parse($this->intent['start_date']);
        $end   = \Carbon\Carbon::parse($this->intent['end_date']);
        // Calculate months (minimum 1)
        $months =
            ($end->year - $start->year) * 12 +
            ($end->month - $start->month);

        $months = max($months, 1);

        $amount = $months * $this->apartment->monthly_price;

        // dd('data ' . $amount . ' and ' . $end);

        try {
            app(BookingService::class)->create([
                'apartment_id' => $this->apartment->id,
                'guest_name'   => $this->data['guest_name'],
                'guest_email'  => $this->data['guest_email'],
                'user_id'      => Auth::id(),
                'start_date'   => $this->intent['start_date'],
                'end_date'     => $this->intent['end_date'],
                'amount'       => $amount,
            ]);
        } catch (BookingUnavailableException $e) {
            Notification::make()
                ->title("Apartment not available")
                ->body($e->getMessage())
                ->warning()
                ->send();

            // $this->halt();
            return;
        }

        session()->forget('booking.intent');

        $this->redirect('/guest/bookings');
    }
}

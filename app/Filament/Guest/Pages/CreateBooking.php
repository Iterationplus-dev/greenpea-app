<?php

namespace App\Filament\Guest\Pages;

use Filament\Forms;
use App\Models\Booking;
use Filament\Pages\Page;
use App\Models\Apartment;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Section;
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

        // Prefill form data
        $this->data = [
            'guest_name'  => Auth::user()->name,
            'guest_email' => Auth::user()->email,
            'start_date'  => $this->intent['start_date'],
            'end_date'    => $this->intent['end_date'],
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Booking details')
                    ->schema([
                        Forms\Components\TextInput::make('guest_name')
                            ->label('Full name')
                            ->required(),

                        Forms\Components\TextInput::make('guest_email')
                            ->label('Email address')
                            ->email()
                            ->required(),

                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->disabled(),

                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->disabled(),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->data;

        $start = \Carbon\Carbon::parse($this->intent['start_date']);
        $end   = \Carbon\Carbon::parse($this->intent['end_date']);

        // Calculate number of months (minimum 1)
        $months =
            ($end->year - $start->year) * 12 +
            ($end->month - $start->month);

        $months = max($months, 1);
        // dd($data);
        // Calculate total amount
        $amount = $months * $this->apartment->monthly_price;

        // dd($amount);

        Booking::create([
            'apartment_id' => $this->apartment->id,
            'user_id'      => auth()->id(), // nullable-safe
            'guest_name'   => $data['guest_name'],
            'guest_email'  => $data['guest_email'],
            'start_date'   => $start,
            'end_date'     => $end,
            'amount'       => $amount,
            'total_amount' => $amount,
            'net_amount' => $amount,
            'status'       => 'pending',
        ]);

        session()->forget('booking.intent');
        $this->redirect('/guest/bookings');
    }
}

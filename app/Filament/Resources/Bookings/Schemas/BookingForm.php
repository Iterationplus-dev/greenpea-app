<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Apartment;
use App\Models\Booking;
use App\Enums\PaymentMethod;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Select Existing Guest')
                ->schema([
                    Select::make('user_id')
                        ->label('Search Guest')
                        ->options(User::query()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->reactive(),
                ]),

            Section::make('Create New Guest')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('guest_name')
                            ->label('Full name')
                            ->placeholder('Enter guest full name'),
                        TextInput::make('guest_email')->email()
                            ->label('Valid email address')
                            ->placeholder('Enter guest email address'),
                        TextInput::make('guest_phone')
                            ->label('Phone number')
                            ->placeholder('Enter guest phone number'),
                        Toggle::make('guest_status')->default(true)
                            ->label('Status')
                            ->helperText('Activate guest account upon creation'),
                    ]),
                ])
                ->visible(fn($get) => ! $get('user_id')),

            Section::make('Booking Details')
                ->schema([

                    Grid::make(2)->schema([

                        Select::make('apartment')
                            ->label('Apartment')
                            ->options(Apartment::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculatePrice($get, $set);
                            }),

                        TextInput::make('calculated_amount')
                            ->label('Total Amount')
                            ->prefix(setting('currency'))
                            ->disabled()
                            ->extraAttributes(['class' => 'font-bold text-lg text-danger-700'])
                    ]),

                    Grid::make(2)->schema([

                        DatePicker::make('start_date')
                            ->label('Check-In')
                            ->minDate(now())
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->placeholder('Select check-in date')
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if (! $state) return;

                                $set(
                                    'end_date',
                                    Carbon::parse($state)->addMonth()->toDateString()
                                );

                                self::calculatePrice($get, $set);
                            }),

                        DatePicker::make('end_date')
                            ->label('Check-Out')
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->placeholder('Select check-out date')
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculatePrice($get, $set);
                            }),
                    ]),

                    Textarea::make('conflict_details')
                        ->disabled()
                        ->visible(fn($get) => ! empty($get('conflict_details'))),

                    Textarea::make('occupied_calendar')
                        ->disabled()
                        ->visible(fn($get) => ! empty($get('occupied_calendar'))),
                ]),

            Section::make('Walk-In Payment')
                ->schema([
                    Select::make('payment_method')
                        ->options([
                            PaymentMethod::CASH->value => 'Cash',
                            PaymentMethod::BANK_TRANSFER->value => 'Bank Transfer',
                        ])
                        ->required()
                        ->preload()
                        ->searchable()
                        ->columns(1),

                    TextInput::make('amount_received')
                        ->required()
                        ->columns(1)
                        ->prefix(setting('currency'))
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (! $state) return;

                            // Clean existing commas
                            $clean = str_replace(',', '', $state);

                            // Format nicely
                            $set('amount_received', number_format((float) $clean, 2));
                        })
                        ->dehydrateStateUsing(function ($state) {
                            // Always remove commas before saving
                            return $state ? str_replace(',', '', $state) : null;
                        }),


                    RichEditor::make('admin_note')
                        ->toolbarButtons([
                            ['bold', 'italic'],
                            ['attachFiles'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                            ['undo', 'redo'],
                        ])
                        ->label('Booking note / Payment reference')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function getConflictingBooking(array $data)
    {
        if (empty($data['apartment']) || empty($data['start_date']) || empty($data['end_date'])) {
            return null;
        }

        return Booking::where('apartment_id', $data['apartment'])
            ->where(function ($query) use ($data) {
                $query
                    ->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
            ->first();
    }

    public static function getOccupiedRanges(array $data): array
    {
        if (empty($data['apartment'])) {
            return [];
        }

        return Booking::where('apartment_id', $data['apartment'])
            ->orderBy('start_date')
            ->get(['reference', 'start_date', 'end_date'])
            ->map(
                fn($b) =>
                "{$b->reference}: {$b->start_date} → {$b->end_date}"
            )
            ->toArray();
    }

    protected static function calculatePrice(callable $get, callable $set): void
    {
        $apartmentId = $get('apartment');
        $start = $get('start_date');
        $end = $get('end_date');

        if (! $apartmentId || ! $start || ! $end) {
            $set('calculated_amount', null);
            return;
        }

        $apartment = Apartment::find($apartmentId);

        if (! $apartment) {
            $set('calculated_amount', null);
            return;
        }

        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        // Calculate months (round up partial months)
        $months = max(1, $startDate->diffInMonths($endDate) + 1);

        $total = number_format($months * $apartment->monthly_price, 2);

        $set('calculated_amount', $total);
    }
}

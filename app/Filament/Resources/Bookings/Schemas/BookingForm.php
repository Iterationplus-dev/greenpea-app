<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //Form view only
                Section::make('Booking Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')->disabled(),
                        TextInput::make('guest_name')->disabled(),
                        TextInput::make('amount')->prefix('₦')->disabled(),
                        TextInput::make('status')->disabled(),
                        DatePicker::make('start_date')
                            ->disabled()
                            ->native(false)
                            ->placeholder('Start Date'),
                        DatePicker::make('end_date')
                            ->disabled()
                            ->native(false)
                            ->placeholder('End Date'),
                    ])->columnSpanFull(),
            ]);
    }
}

<?php

namespace App\Filament\Guest\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('discount_id')
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('apartment_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('months')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('net_amount')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'partially_paid' => 'Partially paid',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ])
                    ->default('pending')
                    ->required(),
                TextInput::make('paystack_reference'),
                TextInput::make('paystack_transaction_id'),
            ]);
    }
}

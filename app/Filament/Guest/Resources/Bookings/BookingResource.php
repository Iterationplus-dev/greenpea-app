<?php

namespace App\Filament\Guest\Resources\Bookings;

use UnitEnum;
use BackedEnum;
use App\Models\Booking;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Guest\Resources\Bookings\Pages\EditBooking;
use App\Filament\Guest\Resources\Bookings\Pages\ListBookings;
use App\Filament\Guest\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Guest\Resources\Bookings\Schemas\BookingForm;
use App\Filament\Guest\Resources\Bookings\Tables\BookingsTable;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'booking';
    protected static ?string $navigationLabel = 'My Bookings';
    protected static string | UnitEnum | null $navigationGroup = 'Bookings';

    /** Scope bookings to logged-in guest */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($query) {
                $query
                    ->where('user_id', auth()->id())
                    ->orWhere('guest_email', auth()->user()->email);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return BookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}

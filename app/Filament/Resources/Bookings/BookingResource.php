<?php

namespace App\Filament\Resources\Bookings;

use UnitEnum;
use BackedEnum;
use App\Enums\UserRole;
use App\Models\Booking;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Bookings\Pages\EditBooking;
use App\Filament\Resources\Bookings\Pages\ViewBooking;
use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Filament\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Resources\Bookings\Schemas\BookingForm;
use App\Filament\Resources\Bookings\Tables\BookingsTable;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static string | UnitEnum | null $navigationGroup = 'Bookings';
    protected static ?string $navigationLabel = 'Manage Bookings';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'Booking';

    // SCOPING
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // single role admin can see all bookings //(! auth()->user()->hasRole('admin')
        if (! auth()->user()->hasAnyRole([UserRole::SUPER_ADMIN->value, UserRole::ADMIN->value])) {
            $query->whereHas(
                'apartment.property',
                fn($q) =>
                $q->where('owner_id', auth()->id())
            );
        }

        return $query;
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
            'view' => ViewBooking::route('/[record]'),
            // 'create' => CreateBooking::route('/create'),
            // 'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}


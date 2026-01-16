<?php

namespace App\Filament\Resources\Bookings;

use App\Enums\GroupLabel;
use UnitEnum;
use BackedEnum;
use App\Models\Booking;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Bookings\Pages\ViewBooking;
use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Filament\Resources\Bookings\Schemas\BookingForm;
use App\Filament\Resources\Bookings\Tables\BookingsTable;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::BOOKINGS;
    protected static ?string $navigationLabel = 'Manage Bookings';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'reference';

    /**
     * IMPORTANT:
     * We now scope using ADMIN, not User
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $admin = auth('admin')->user();

        // Super Admin & CEO see everything
        if ($admin->isSuper() || $admin->type->value === 'ceo') {
            return $query;
        }


        // Property owners only see bookings for their properties
        if ($admin->type->value === 'owner') {
            $query->whereHas('apartment.property', fn ($q) =>
                $q->where('owner_id', $admin->id)
            );
        }

        // Managers & staff see everything for now (can later be region-scoped)
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

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'view' => ViewBooking::route('/{record}'),
        ];
    }

    
}

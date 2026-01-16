<?php

namespace App\Filament\Resources\BookingPayments;

use BackedEnum;
use UnitEnum;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\BookingPayment;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\BookingPayments\Pages\EditBookingPayment;
use App\Filament\Resources\BookingPayments\Pages\ListBookingPayments;
use App\Filament\Resources\BookingPayments\Pages\CreateBookingPayment;
use App\Filament\Resources\BookingPayments\Schemas\BookingPaymentForm;
use App\Filament\Resources\BookingPayments\Tables\BookingPaymentsTable;

class BookingPaymentResource extends Resource
{
    protected static ?string $model = BookingPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::BOOKINGS;

    protected static ?string $recordTitleAttribute = 'BookingPayment';
     protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return BookingPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingPaymentsTable::configure($table);
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
            'index' => ListBookingPayments::route('/'),
            'create' => CreateBookingPayment::route('/create'),
            'edit' => EditBookingPayment::route('/{record}/edit'),
        ];
    }
}

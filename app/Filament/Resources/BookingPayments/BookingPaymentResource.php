<?php

namespace App\Filament\Resources\BookingPayments;

use App\Filament\Resources\BookingPayments\Pages\CreateBookingPayment;
use App\Filament\Resources\BookingPayments\Pages\EditBookingPayment;
use App\Filament\Resources\BookingPayments\Pages\ListBookingPayments;
use App\Filament\Resources\BookingPayments\Schemas\BookingPaymentForm;
use App\Filament\Resources\BookingPayments\Tables\BookingPaymentsTable;
use App\Models\BookingPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingPaymentResource extends Resource
{
    protected static ?string $model = BookingPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'BookingPayment';

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

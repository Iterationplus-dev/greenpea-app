<?php

namespace App\Filament\Exports;

use App\Models\BookingPayment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class BookingPaymentExporter extends Exporter
{
    protected static ?string $model = BookingPayment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('reference')->label('Reference'),

            ExportColumn::make('booking.guest_name')->label('Guest'),

            ExportColumn::make('booking.apartment.name')->label('Apartment'),

            ExportColumn::make('amount')->label('Amount'),

            ExportColumn::make('gateway')->label('Gateway'),

            ExportColumn::make('status')->label('Status'),

            ExportColumn::make('payment_method')->label('Method'),

            ExportColumn::make('payment_date')->label('Paid At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your booking payment export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return 'booking-payments-' . now()->format('Y-m-d');
    }
}

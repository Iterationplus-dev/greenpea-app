<?php

namespace App\Filament\Resources\BookingPayments\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\BookingPayments\BookingPaymentResource;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class ListBookingPayments extends ListRecords
{
    protected static string $resource = BookingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            // FilamentExportBulkAction::make('Export'),
            FilamentExportHeaderAction::make('export')

        ];
    }

    protected function getTableBulkActions(): array
{
    return [
        FilamentExportBulkAction::make('Export'),
    ];
}
}

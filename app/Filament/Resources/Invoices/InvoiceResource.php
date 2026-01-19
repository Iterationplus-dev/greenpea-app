<?php

namespace App\Filament\Resources\Invoices;

use UnitEnum;
use BackedEnum;
use App\Models\Invoice;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Schemas\InvoiceForm;
use App\Filament\Resources\Invoices\Tables\InvoicesTable;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    //

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentMinus;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::FINANCE;
    protected static ?string $navigationLabel = 'Invoices';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'Invoice';

    public static function form(Schema $schema): Schema
    {
        return InvoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvoicesTable::configure($table);
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
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
}

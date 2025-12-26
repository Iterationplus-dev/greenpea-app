<?php

namespace App\Filament\Resources\OwnerEarnings;

use App\Filament\Resources\OwnerEarnings\Pages\CreateOwnerEarning;
use App\Filament\Resources\OwnerEarnings\Pages\EditOwnerEarning;
use App\Filament\Resources\OwnerEarnings\Pages\ListOwnerEarnings;
use App\Filament\Resources\OwnerEarnings\Schemas\OwnerEarningForm;
use App\Filament\Resources\OwnerEarnings\Tables\OwnerEarningsTable;
use App\Models\OwnerEarning;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OwnerEarningResource extends Resource
{
    protected static ?string $model = OwnerEarning::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'OwnerEarning';

    public static function form(Schema $schema): Schema
    {
        return OwnerEarningForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OwnerEarningsTable::configure($table);
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
            'index' => ListOwnerEarnings::route('/'),
            'create' => CreateOwnerEarning::route('/create'),
            'edit' => EditOwnerEarning::route('/{record}/edit'),
        ];
    }
}

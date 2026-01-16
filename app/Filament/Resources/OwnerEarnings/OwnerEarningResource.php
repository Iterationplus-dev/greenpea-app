<?php

namespace App\Filament\Resources\OwnerEarnings;

use UnitEnum;
use BackedEnum;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use App\Models\OwnerEarning;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\OwnerEarnings\Pages\EditOwnerEarning;
use App\Filament\Resources\OwnerEarnings\Pages\ListOwnerEarnings;
use App\Filament\Resources\OwnerEarnings\Pages\CreateOwnerEarning;
use App\Filament\Resources\OwnerEarnings\Schemas\OwnerEarningForm;
use App\Filament\Resources\OwnerEarnings\Tables\OwnerEarningsTable;

class OwnerEarningResource extends Resource
{
    protected static ?string $model = OwnerEarning::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::FINANCE;
    protected static ?string $navigationLabel = 'Earnings';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'OwnerEarning';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

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

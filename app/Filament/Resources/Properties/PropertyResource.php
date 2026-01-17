<?php

namespace App\Filament\Resources\Properties;

use UnitEnum;
use BackedEnum;
use App\Models\Property;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Properties\Pages\EditProperty;
use App\Filament\Resources\Properties\Pages\CreateProperty;
use App\Filament\Resources\Properties\Pages\ListProperties;
use App\Filament\Resources\Properties\Schemas\PropertyForm;
use App\Filament\Resources\Properties\Tables\PropertiesTable;
use App\Filament\Resources\Properties\RelationManagers\ApartmentsRelationManager;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;
    // protected static ?string $navigationGroup = 'Properties';
    protected static string|UnitEnum|null $navigationGroup = GroupLabel::FACILITYMGT;
    protected static ?string $navigationLabel = 'Properties';
    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'property';

    public static function getNavigationGroup(): ?string
    {
        return strtoupper(GroupLabel::FACILITYMGT->value);
    }

    /* ============OWNER / ADMIN SCOPING ========= */

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()->isSuper()) {
            $query->where('owner_id', auth()->id());
        }
        return $query;
    }


    public static function form(Schema $schema): Schema
    {
        return PropertyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
          ApartmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
            'edit' => EditProperty::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Properties;

use App\Enums\GroupLabel;
use UnitEnum;
use BackedEnum;
use App\Models\Property;
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

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;
    // protected static ?string $navigationGroup = 'Properties';
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::FACILITYMGT->value;
    protected static ?string $navigationLabel = 'Properties';
    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'property';

    /* ============OWNER / ADMIN SCOPING ========= */

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // For non-super-admin users, restrict to their own properties
        // if (! auth()->user()->isAdmin()) {
        //     $query->where('owner_id', auth()->id());
        // }

        if (! auth()->user()->isSuperAdmin()) {
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
        //   ApartmentsRelationManager::class,
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

<?php

namespace App\Filament\Resources\Apartments;

use App\Enums\GroupLabel;
use App\Filament\Resources\Apartments\Pages\CreateApartment;
use App\Filament\Resources\Apartments\Pages\EditApartment;
use App\Filament\Resources\Apartments\Pages\ListApartments;
use App\Filament\Resources\Apartments\Schemas\ApartmentForm;
use App\Filament\Resources\Apartments\Tables\ApartmentsTable;
use App\Models\Apartment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ApartmentResource extends Resource
{
    protected static ?string $model = Apartment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Apartments';

    protected static string|UnitEnum|null $navigationGroup = GroupLabel::FACILITYMGT;

    protected static ?string $recordTitleAttribute = 'apartment';

    protected static ?int $navigationSort = 1;

    /**
     * Scope apartments based on admin type
     */
    public static function getNavigationGroup(): ?string
    {
        return strtoupper(GroupLabel::FACILITYMGT->value);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Super Admin / CEO / Manager → see all
        if (admin()?->isSuper() || admin()?->canManageProperties()) {
            return $query;
        }

        // Owner admins → only their properties
        if (admin()?->type?->value === 'owner') {
            $query->whereHas(
                'property',
                fn ($q) => $q->where('owner_id', admin()->id)
            );
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return ApartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApartmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Apartments\RelationManagers\ImagesRelationManager::class,
            \App\Filament\Resources\Apartments\RelationManagers\AmenitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApartments::route('/'),
            'create' => CreateApartment::route('/create'),
            'edit' => EditApartment::route('/{record}/edit'),
        ];
    }
}

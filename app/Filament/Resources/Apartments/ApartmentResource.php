<?php

namespace App\Filament\Resources\Apartments;

use UnitEnum;
use BackedEnum;
use App\Enums\UserRole;
use App\Models\Apartment;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Apartments\Pages\EditApartment;
use App\Filament\Resources\Apartments\Pages\ListApartments;
use App\Filament\Resources\Apartments\Pages\CreateApartment;
use App\Filament\Resources\Apartments\Schemas\ApartmentForm;
use App\Filament\Resources\Apartments\Tables\ApartmentsTable;

class ApartmentResource extends Resource
{
    protected static ?string $model = Apartment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'Apartment';
    protected static ?string $navigationLabel = 'Apartments';
    protected static string | UnitEnum | null $navigationGroup = 'Properties';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Property owners only see their apartments
        if (! auth()->user()->hasAnyRole([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value])) {
            $query->whereHas(
                'property',
                fn($q) =>
                $q->where('owner_id', auth()->id())
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
            //
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

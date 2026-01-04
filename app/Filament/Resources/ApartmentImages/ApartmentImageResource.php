<?php

namespace App\Filament\Resources\ApartmentImages;

use App\Filament\Resources\ApartmentImages\Pages\CreateApartmentImage;
use App\Filament\Resources\ApartmentImages\Pages\EditApartmentImage;
use App\Filament\Resources\ApartmentImages\Pages\ListApartmentImages;
use App\Filament\Resources\ApartmentImages\Schemas\ApartmentImageForm;
use App\Filament\Resources\ApartmentImages\Tables\ApartmentImagesTable;
use App\Models\ApartmentImage;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApartmentImageResource extends Resource
{
    protected static ?string $model = ApartmentImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
     protected static ?string $navigationLabel = 'Apartments';
    protected static string|UnitEnum|null $navigationGroup = 'Properties';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ApartmentImageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApartmentImagesTable::configure($table);
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
            'index' => ListApartmentImages::route('/'),
            'create' => CreateApartmentImage::route('/create'),
            'edit' => EditApartmentImage::route('/{record}/edit'),
        ];
    }
}

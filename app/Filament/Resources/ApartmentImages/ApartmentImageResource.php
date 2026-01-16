<?php

namespace App\Filament\Resources\ApartmentImages;

use UnitEnum;
use BackedEnum;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\ApartmentImage;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\ApartmentImages\Pages\EditApartmentImage;
use App\Filament\Resources\ApartmentImages\Pages\ListApartmentImages;
use App\Filament\Resources\ApartmentImages\Pages\CreateApartmentImage;
use App\Filament\Resources\ApartmentImages\Schemas\ApartmentImageForm;
use App\Filament\Resources\ApartmentImages\Tables\ApartmentImagesTable;

class ApartmentImageResource extends Resource
{
    protected static ?string $model = ApartmentImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
    protected static ?string $navigationLabel = 'Apartment Images';
    protected static string|UnitEnum|null $navigationGroup = GroupLabel::FACILITYMGT;
    protected static ?string $recordTitleAttribute = 'apartment-image';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return strtoupper(GroupLabel::FACILITYMGT->value);
    }

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

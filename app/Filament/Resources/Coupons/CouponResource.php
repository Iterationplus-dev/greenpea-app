<?php

namespace App\Filament\Resources\Coupons;

use UnitEnum;
use BackedEnum;
use App\Models\Coupon;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Schemas\CouponForm;
use App\Filament\Resources\Coupons\Tables\CouponsTable;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::SETTINGS;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Coupon';

    protected static ?string $recordTitleAttribute = 'Coupon';

    public static function form(Schema $schema): Schema
    {
        return CouponForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CouponsTable::configure($table);
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
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Wallets;

use UnitEnum;
use BackedEnum;
use App\Models\Wallet;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Wallets\Pages\EditWallet;
use App\Filament\Resources\Wallets\Pages\ListWallets;
use App\Filament\Resources\Wallets\Pages\CreateWallet;
use App\Filament\Resources\Wallets\RelationManagers\TransactionsRelationManager;
use App\Filament\Resources\Wallets\Schemas\WalletForm;
use App\Filament\Resources\Wallets\Tables\WalletsTable;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWallet;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::FINANCE;
    protected static ?string $recordTitleAttribute = 'Wallet';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
         ->with('transactions');
    }
    public static function form(Schema $schema): Schema
    {
        return WalletForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WalletsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWallets::route('/'),
            'create' => CreateWallet::route('/create'),
            'edit' => EditWallet::route('/{record}/edit'),
        ];
    }
}

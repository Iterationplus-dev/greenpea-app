<?php

namespace App\Filament\Guest\Resources\Wallets;

use UnitEnum;
use BackedEnum;
use App\Models\Wallet;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Guest\Resources\Wallets\Pages\EditWallet;
use App\Filament\Guest\Resources\Wallets\Pages\ListWallets;
use App\Filament\Guest\Resources\Wallets\Pages\CreateWallet;
use App\Filament\Guest\Resources\Wallets\Schemas\WalletForm;
use App\Filament\Guest\Resources\Wallets\Tables\WalletsTable;
use App\Filament\Guest\Resources\Wallets\RelationManagers\TransactionsRelationManager;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWallet;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::FINANCE->value;
    protected static ?string $navigationLabel = 'My Wallet';

    protected static ?string $recordTitleAttribute = 'wallet';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('transactions')
            ->where('user_id', auth()->id());
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
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

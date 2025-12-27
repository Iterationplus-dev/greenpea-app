<?php

namespace App\Filament\Resources\WalletTransactions;

use App\Enums\UserRole;
use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\WalletTransaction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WalletTransactions\Pages\EditWalletTransaction;
use App\Filament\Resources\WalletTransactions\Pages\ListWalletTransactions;
use App\Filament\Resources\WalletTransactions\Pages\CreateWalletTransaction;
use App\Filament\Resources\WalletTransactions\Schemas\WalletTransactionForm;
use App\Filament\Resources\WalletTransactions\Tables\WalletTransactionsTable;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWallet;
    protected static string | UnitEnum | null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Wallet Transactions';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'WalletTransaction';

     public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()->hasAnyRole([UserRole::SUPER_ADMIN->value, UserRole::ADMIN->value])) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return WalletTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WalletTransactionsTable::configure($table);
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
            'index' => ListWalletTransactions::route('/'),
            // 'create' => CreateWalletTransaction::route('/create'),
            // 'edit' => EditWalletTransaction::route('/{record}/edit'),
        ];
    }
}

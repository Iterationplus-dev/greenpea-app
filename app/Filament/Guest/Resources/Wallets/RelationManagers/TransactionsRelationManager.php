<?php

namespace App\Filament\Guest\Resources\Wallets\RelationManagers;

use App\Enums\WalletTransType;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';
    protected static ?string $title = 'Wallet Transactions';



    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('wallet')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query)
            ->emptyStateIcon('heroicon-o-wallet')
            ->emptyStateHeading('No wallet transactions found!')
            ->emptyStateDescription('You don\'t have wallet transactions yet.')
            ->emptyStateActions([
                // CreateAction::make(),
            ])
            ->paginatedWhileReordering()

            ->searchable()
            ->recordTitleAttribute('wallet')
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        WalletTransType::CREDIT->value => 'success',
                        WalletTransType::DEBIT->value => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state) => match ($state) {
                        WalletTransType::CREDIT->value => 'heroicon-o-check-circle',
                        WalletTransType::DEBIT->value => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->extraAttributes([
                        // 'class' => 'px-2 py-0.5 text-xs rounded-md',
                         'class' => 'px-1.5 py-0.1 text-[10px] rounded',
                    ]),

                TextColumn::make('amount')
                    ->money('NGN')
                    ->weight('bold')
                    ->alignLeft(),

                TextColumn::make('reference')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
            ])
            ->deferLoading()
            // ->paginated(false)
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}

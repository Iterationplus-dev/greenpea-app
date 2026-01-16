<?php

namespace App\Filament\Resources\WalletTransactions\Tables;

use Filament\Tables\Table;
use App\Enums\WalletTransType;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class WalletTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->orderBy('created_at', 'asc'))
            ->emptyStateIcon('heroicon-o-wallet')
            ->emptyStateHeading('No wallet transactions found!')
            ->emptyStateDescription('You don\'t have wallet transactions yet.')
            ->emptyStateActions([
                // CreateAction::make(),
            ])
            ->paginatedWhileReordering()
            ->deferLoading()
            ->searchable()
            ->columns([
                //
                TextColumn::make('reference')
                    ->label('Ref#')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('type')
                    ->badge()

                    ->color(fn($state) => match (WalletTransType::tryFrom($state)) {
                        WalletTransType::CREDIT => 'success',
                        WalletTransType::DEBIT => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn($state) => match (WalletTransType::tryFrom($state)) {
                        WalletTransType::CREDIT => 'heroicon-o-plus-circle',
                        WalletTransType::DEBIT => 'heroicon-o-minus-circle',
                        default => null,
                    })
                    ->alignCenter(),
                TextColumn::make('amount')
                    ->money('NGN')
                    ->label('Amount')
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

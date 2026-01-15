<?php

namespace App\Filament\Resources\Wallets\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;

class WalletsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('balance', 'desc')

            ->modifyQueryUsing(fn (Builder $query) =>
                $query->with('user')
            )

            ->emptyStateIcon('heroicon-o-wallet')
            ->emptyStateHeading('No wallet records yet')
            ->emptyStateDescription('Wallets are created automatically when customers make payments.')

            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->money('NGN')
                    ->sortable()
                    // ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                    ->weight('bold'),

                TextColumn::make('transactions_count')
                    ->counts('transactions')
                    ->label('Transactions')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Last Activity')
                    ->dateTime()
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('balance')
                    ->label('Balance Status')
                    ->options([
                        'positive' => 'Has Balance',
                        'zero' => 'Zero Balance',
                    ])
                    ->query(fn ($query, $value) => match ($value) {
                        'positive' => $query->where('balance', '>', 0),
                        'zero' => $query->where('balance', 0),
                        default => $query,
                    }),
            ])

            ->recordActions([
                // EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ])

            ->deferLoading();
    }
}

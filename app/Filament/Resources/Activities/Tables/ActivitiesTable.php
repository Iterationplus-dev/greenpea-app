<?php

namespace App\Filament\Resources\Activities\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ActivitiesTable
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
                TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),

                TextColumn::make('description')
                    ->label('Description')
                    ->wrap()
                    ->extraAttributes(['class' => 'custom-padding-left-column']),

                TextColumn::make('subject_type')
                    ->label('Model')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

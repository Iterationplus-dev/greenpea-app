<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->emptyStateHeading('No settings found!')
            ->emptyStateDescription('You don\'t have settings yet.')
            ->emptyStateActions([])
            ->columns([
                TextColumn::make('key')
                    ->label('Setting')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->limit(60)
                    ->wrap(),

                TextColumn::make('type')->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActionsColumnLabel('Manage')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}

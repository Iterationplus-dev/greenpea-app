<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('name', 'asc')
            ->modifyQueryUsing(fn(Builder $query) => $query)
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('No roles found!')
            ->emptyStateDescription('You don\'t have roles yet. Click the button to add a role.')
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->paginatedWhileReordering()
            ->deferLoading()
            ->columns([
                //
                TextColumn::make('name')->searchable(),
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('Permissions'),
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

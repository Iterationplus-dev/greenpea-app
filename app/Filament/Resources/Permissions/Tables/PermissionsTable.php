<?php

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->striped()
            ->defaultSort('name', 'asc')
            ->modifyQueryUsing(fn(Builder $query) => $query)
            ->emptyStateIcon('heroicon-o-key')
            ->emptyStateHeading('No permissions found!')
            ->emptyStateDescription('You don\'t have permissions yet. Click the button to add a permission.')
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->paginatedWhileReordering()
            ->deferLoading()
            ->columns([
                //
               TextColumn::make('name')->searchable(),
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

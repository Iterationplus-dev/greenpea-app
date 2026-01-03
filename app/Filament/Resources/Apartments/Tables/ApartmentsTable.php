<?php

namespace App\Filament\Resources\Apartments\Tables;

use App\Enums\UserRole;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;

class ApartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('name', 'asc')
            // ->modifyQueryUsing(fn(Builder $query) => $query)
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                if ($user->hasRole(UserRole::PROPERTY_OWNER)) {
                    $query->whereHas('apartment.property', function ($q) use ($user) {
                        $q->where('owner_id', $user->id);
                    });
                }
            })

            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('No apartment found!')
            ->emptyStateDescription('You don\'t have apartment yet. Click the button to add.')
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->paginatedWhileReordering()
            ->deferLoading()
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('property.name')->label('Property'),
                TextColumn::make('property.city')->label('City'),
                TextColumn::make('price_per_month')->money('NGN'),
                ToggleColumn::make('is_available')
                    ->label('Available')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->width('5')
                    ->alignCenter()
                    ->sortable()
                    ->columnSpan(1),
            ])
            ->defaultSort('name')
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

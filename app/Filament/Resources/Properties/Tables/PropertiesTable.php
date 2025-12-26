<?php

namespace App\Filament\Resources\Properties\Tables;

use App\Models\Property;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query
                // ->orderBy('created_at', 'desc')
                ->orderBy('created_at', 'asc'))
            ->emptyStateIcon('heroicon-o-building-office-2')
            ->emptyStateHeading('No properties found!')
            ->emptyStateDescription('You don\'t have properties yet. Click the button to add a property.')
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->paginatedWhileReordering()
            ->deferLoading()
            ->searchable()
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Property Name')
                    ->limit(30)
                    ->sortable(),

                TextColumn::make('city')
                ->label('City')
                    ->badge()
                    ->sortable(),

                TextColumn::make('apartments_count')
                    ->counts('apartments')
                    ->label('Apartments')
                    ->alignCenter()
                    ->visibleFrom('md'),

                TextColumn::make('bookings_count')
                    ->counts('bookings')
                    ->alignCenter()
                    ->label('Bookings')
                    ->visibleFrom('md'),

                ToggleColumn::make('status')
                    ->label('Status')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->width('5')
                    ->alignCenter()
                    ->sortable()
                    ->visibleFrom('md'),

                TextColumn::make('created_at')
                    ->date()
                    ->label('Created')
                    ->visibleFrom('md')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('city')
                    ->options([
                        'Abuja' => 'Abuja',
                        'Lagos' => 'Lagos',
                        'Port Harcourt' => 'Port Harcourt',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    // EditAction::make(),
                    ViewAction::make(),

                    EditAction::make(),

                    Action::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->visible(fn(Property $record) => $record->status === 'inactive')
                        ->action(
                            fn(Property $record) =>
                            $record->update(['status' => 'active'])
                        ),

                    Action::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->visible(fn(Property $record) => $record->status === 'active')
                        ->action(
                            fn(Property $record) =>
                            $record->update(['status' => 'inactive'])
                        ),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

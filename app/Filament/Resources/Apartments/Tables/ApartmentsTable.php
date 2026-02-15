<?php

namespace App\Filament\Resources\Apartments\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;

class ApartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('name', 'asc')
            ->deferLoading(fn() => ! request()->has('record'))
            ->modifyQueryUsing(function (Builder $query) {
                if ($recordId = request()->query('record')) {
                    $query->where('id', $recordId);
                }
            })
            ->emptyStateIcon('heroicon-o-building-office-2')
            ->emptyStateHeading('No apartments found')
            ->emptyStateDescription('You have not added any apartments yet.')
            ->emptyStateActions([
                CreateAction::make(),
            ])

            ->paginatedWhileReordering()
            // ->deferLoading()

            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('property.name')
                    ->label('Property')
                    ->sortable(),

                TextColumn::make('property.city')
                    ->label('City')
                    ->sortable(),

                TextColumn::make('monthly_price')
                    ->money('NGN')
                    ->sortable()
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                ToggleColumn::make('is_available')
                    ->label('Available')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->recordClasses(
                fn($record) =>
                request('record') == $record->id
                    ? 'highlighted-row'
                    : null
            )

            ->recordActionsColumnLabel('Action')
            ->recordActions([
                ActionGroup::make([
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Apartment Deleted')
                                ->body('The apartment details deleted successfully')
                        ),

                    EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->label('Manage')
                        ->tooltip('Edit this record')
                        ->modalHeading('Edit Apartment')
                        ->color('info'),
                ])
                    ->icon('heroicon-o-ellipsis-vertical')
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make()
                        // ->chunkSelectedRecords(10),
                ]),
            ]);
    }
}

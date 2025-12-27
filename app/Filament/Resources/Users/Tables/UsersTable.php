<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserStatus;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Database\Query\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query)
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('No users found!')
            ->emptyStateDescription('You don\'t have users yet. Click the button to add a user.')
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->paginatedWhileReordering()
            ->deferLoading()
            ->columns([
                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->visibleFrom('sm')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('role')
                    ->label('Role')
                    ->searchable()
                    ->sortable()
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->extraAttributes(['class' => 'custom-padding-left-column']),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(UserStatus $state) => match ($state) {
                        UserStatus::ACTIVE => 'success',
                        UserStatus::INACTIVE => 'gray',
                        UserStatus::SUSPENDED => 'warning',
                        UserStatus::BANNED => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(UserStatus $state) => match ($state) {
                        UserStatus::ACTIVE => 'heroicon-o-check-circle',
                        UserStatus::INACTIVE => 'heroicon-o-pause-circle',
                        UserStatus::SUSPENDED => 'heroicon-o-x-circle',
                        UserStatus::BANNED => 'heroicon-o-no-symbol',
                        default => null,
                    })
                    ->formatStateUsing(fn(UserStatus $state) => ucfirst($state->value))
                    ->extraAttributes(['class' => 'custom-padding-left-column']),

                // Actions column removed; use recordActions for row actions
            ])
            ->filters([
                //
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        collect(UserStatus::cases())
                            ->mapWithKeys(fn($status) => [
                                $status->value => ucfirst($status->value),
                            ])
                    )
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->label('Edit')
                    ->tooltip('Edit this record')
                    ->modalHeading('Edit User'),

            ])
            ->recordActionsColumnLabel('Actions')

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

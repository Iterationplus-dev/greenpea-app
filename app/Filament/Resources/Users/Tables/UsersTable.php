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
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->orderBy('created_at', 'asc'))
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
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                // IconColumn::make('status')
                //     ->boolean()
                //     ->label('Status')
                //     ->sortable(),

                // TextColumn::make('status')
                //     ->label('Status')
                //     ->searchable()
                //     ->sortable()
                //     ->visibleFrom('md')
                //     ->badge()
                //     //state => color
                //     ->colors([
                //         UserStatus::ACTIVE->value => 'success',
                //         UserStatus::INACTIVE->value => 'gray',
                //         UserStatus::SUSPENDED->value => 'warning',
                //         UserStatus::BANNED->value => 'danger',
                //     ])
                //     //state => icon (valid heroicons)
                //     ->icons([
                //         UserStatus::ACTIVE->value => 'heroicon-o-check-circle',
                //         UserStatus::INACTIVE->value => 'heroicon-o-pause-circle',
                //         UserStatus::SUSPENDED->value => 'heroicon-o-exclamation-circle',
                //         UserStatus::BANNED->value => 'heroicon-o-x-circle',
                //     ])

                //     //Enum-safe formatting
                //     ->formatStateUsing(fn(UserStatus $state) => ucfirst($state->value)),

                // ToggleColumn::make('status')
                //     ->onColor('success')
                //     ->offColor('danger'),

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
                    ->formatStateUsing(fn(UserStatus $state) => ucfirst($state->value)),


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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

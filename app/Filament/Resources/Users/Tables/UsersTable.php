<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Database\Query\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            // ->modifyQueryUsing(fn(Builder $query) => $query)
            ->deferLoading(fn() => ! request()->has('record'))
            ->modifyQueryUsing(function (Builder $query) {
                if ($recordId = request()->query('record')) {
                    $query->where('id', $recordId);
                }
            })
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('No users found!')
            ->emptyStateDescription('You don\'t have users yet. Click the button to add a user.')
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->paginatedWhileReordering()
            // ->deferLoading()
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
                    // ->formatStateUsing(fn(UserRole $state) => ucfirst($state->value))
                    ->formatStateUsing(fn(UserRole $state) => Str::headline($state->value))
                    // ->formatStateUsing(fn($state) => Str::headline($state))
                    ->badge()
                    ->color(fn(UserRole $state) => match ($state) {
                        UserRole::SUPER_ADMIN => 'success',
                        UserRole::ADMIN => 'primary',
                        UserRole::PROPERTY_OWNER => 'warning',
                        UserRole::AGENT => 'danger',
                        UserRole::MANAGER => 'secondary',
                        UserRole::GUEST => 'info',
                        UserRole::CUSTOMER => 'info',
                        default => 'gray',
                    })
                    ->icon(fn(UserRole $state) => match ($state) {
                        UserRole::SUPER_ADMIN => 'heroicon-o-lock-closed',
                        UserRole::ADMIN => 'heroicon-o-lock-open',
                        UserRole::PROPERTY_OWNER => 'heroicon-o-building-storefront',
                        UserRole::AGENT => 'heroicon-o-megaphone',
                        UserRole::GUEST => 'heroicon-o-briefcase',
                        UserRole::CUSTOMER => 'heroicon-o-briefcase',
                        UserRole::MANAGER => 'heroicon-o-cog',
                        UserRole::MARKETER => 'heroicon-o-megaphone',
                        UserRole::DEVELOPER => 'heroicon-o-building-office-2',
                        UserRole::STAFF => 'heroicon-o-pencil-square',
                        UserRole::ACCOUNTANT => 'heroicon-o-book-open',
                        default => null,
                    })
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
            ->recordClasses(
                fn($record) =>
                request('record') == $record->id
                    ? 'highlighted-row'
                    : null
            )
            ->filters([
                //
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        collect(UserStatus::cases())
                            ->mapWithKeys(fn($status) => [
                                $status->value => ucfirst($status->value),
                            ])
                    ),
                // Filter::make('record')
                //     ->query(
                //         fn($query, array $data) =>
                //         $query->when(
                //             request()->get('record'),
                //             fn($q, $id) => $q->where('id', $id)
                //         )
                //     ),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->label('Edit')
                    ->tooltip('Edit this record')
                    ->modalHeading('Edit User'),
                DeleteAction::make()
                    ->visible(fn(User $record) => $record->id !== auth()->id()),

            ])
            ->recordActionsColumnLabel('Actions')

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

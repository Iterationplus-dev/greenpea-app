<?php

namespace App\Filament\Resources\Admins\Tables;

use App\Enums\AdminType;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class AdminsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                //
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('role')
                    // ->badge()
                    ->colors([
                        'danger' => 'super-admin',
                        'primary' => 'admin',
                    ]),

                TextColumn::make('type')
                    // ->badge()
                    ->color(fn(AdminType $state) => match ($state) {
                        AdminType::CEO => 'success',
                        AdminType::ADMIN => 'info',
                        AdminType::CONSULTANT => 'gray',
                        AdminType::MANAGER => 'warning',
                        AdminType::STAFF => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(AdminType $state) => match ($state) {
                        AdminType::CEO => 'heroicon-o-user',
                        AdminType::ADMIN => 'heroicon-o-user',
                        AdminType::STAFF => 'heroicon-o-users',
                        AdminType::MANAGER => 'heroicon-o-x-circle',
                        AdminType::CONSULTANT => 'heroicon-o-no-symbol',
                        default => null,
                    })
                    ->formatStateUsing(fn(AdminType $state) => ucfirst($state->value))
                    ->extraAttributes(['class' => 'custom-padding-left-column']),

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

            ])->defaultSort('name')
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

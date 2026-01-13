<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->striped()
            ->columns([
                TextColumn::make('number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('booking.reference')
                    ->label('Booking')
                    ->limit(10)
                    ->sortable(),

                TextColumn::make('booking.guest_name')
                    ->label('Guest')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Total')
                    ->money('NGN')
                    ->sortable()
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                TextColumn::make('platform_fee')
                    ->label('Platform Fee')
                    ->money('NGN')
                    ->color('info')
                    ->sortable()
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                TextColumn::make('owner_amount')
                    ->label('Owner Earns')
                    ->money('NGN')
                    ->color('success')
                    ->alignRight()
                    ->getStateUsing(fn ($record) => abs($record->amount - $record->platform_fee))
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'pending',
                        'danger'  => 'cancelled',
                    ]),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActionsColumnLabel('Action')
            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}


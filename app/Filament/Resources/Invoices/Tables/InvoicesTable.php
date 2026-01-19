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
                    ->copyable()
                    ->limit(8),

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
                    ->extraCellAttributes(['class' => 'pr-4'])
                    ->extraHeaderAttributes(['class' => 'pr-4']),

                TextColumn::make('platform_fee')
                    ->label('Platform Fee')
                    // ->money('NGN')
                    ->color('info')
                    ->sortable()
                    ->alignRight()
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->extraCellAttributes(['class' => 'pr-4'])
                    ->extraHeaderAttributes(['class' => 'pr-4']),

                TextColumn::make('owner_amount')
                    ->label('Earnings')
                    // ->money('NGN')
                    ->color('success')
                    ->alignRight()
                    ->getStateUsing(fn($record) => abs($record->amount - $record->platform_fee))
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->extraCellAttributes(['class' => 'pr-4'])
                    ->extraHeaderAttributes(['class' => 'pr-4']),

                TextColumn::make('status')
                    ->badge()
                    ->alignCenter()
                    ->colors([
                        'gray' => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'pending',
                        'danger'  => 'cancelled',
                    ]),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('M d, y')
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
                    // DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}

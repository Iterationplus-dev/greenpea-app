<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Services\InvoiceService;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
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

                // TextColumn::make('booking.reference')
                //     ->label('Booking')
                //     ->limit(10)
                //     ->sortable(),

                TextColumn::make('booking.guest_name')
                    ->label('Guest')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Total')
                    ->money(setting('currency'))
                    ->alignRight()
                    ->extraCellAttributes(['class' => 'pr-4'])
                    ->extraHeaderAttributes(['class' => 'pr-4']),

                TextColumn::make('platform_fee')
                    ->label('Platform Fee')
                    ->money(setting('currency'))
                    ->color('info')
                    ->alignRight()
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->extraCellAttributes(['class' => 'pr-4'])
                    ->extraHeaderAttributes(['class' => 'pr-4']),

                TextColumn::make('owner_amount')
                    ->label('Earnings')
                    ->money(setting('currency'))
                    ->color('success')
                    ->alignRight()
                    ->getStateUsing(fn($record) => abs($record->amount - $record->platform_fee))
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->extraCellAttributes(['class' => 'pr-4'])
                    ->extraHeaderAttributes(['class' => 'pr-4']),

                TextColumn::make('amount_paid')
                    ->label('Paid')
                    ->money(setting('currency'))
                    ->alignRight(),

                TextColumn::make('balance_due')
                    ->label('Balance')
                    ->money(setting('currency'))
                    ->color(fn($state) => $state > 0 ? 'danger' : 'success')
                    ->alignRight(),

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
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->openUrlInNewTab()
                    ->url(fn($record) => $record->pdf_path)
                    ->visible(fn($record) => ! empty($record->pdf_path)),

                Action::make('regenerate')
                    ->label('Regenerate')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        app(InvoiceService::class)
                            ->generateForBooking($record->booking);

                        Notification::make()
                            ->title('Invoice Regenerated')
                            ->body('The invoice has been successfully regenerated.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => admin()?->canManageFinance()),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}

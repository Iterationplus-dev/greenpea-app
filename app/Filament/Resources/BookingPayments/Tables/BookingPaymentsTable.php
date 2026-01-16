<?php

namespace App\Filament\Resources\BookingPayments\Tables;

use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Exports\BookingPaymentExporter;

class BookingPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->deferLoading()
            ->columns([

                TextColumn::make('booking.id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->alignLeft(),

                TextColumn::make('reference')
                    ->label('Ref#')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('apartment')
                    ->label('Apartment')
                    ->state(
                        fn($record) =>
                        optional($record->booking->apartment)->name
                            ?? '—'
                    )
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('booking.apartment', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->wrap(),

                TextColumn::make('guest')
                    ->label('Guest')
                    ->state(
                        fn($record) =>
                        $record->booking->guest_name
                            ?? $record->booking->user?->name
                            ?? '—'
                    )
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('booking', function ($q) use ($search) {
                            $q->where('guest_name', 'like', "%{$search}%")
                                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
                        });
                    }),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn($state) => '₦' . number_format($state, 2))
                    ->sortable()
                    ->weight('bold')
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                TextColumn::make('gateway')
                    ->label('Method')
                    // ->badge()
                    ->color(fn($state) => match ($state) {
                        'paystack' => 'success',
                        'wallet' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    // ->badge()
                    ->color(fn($state) => match ($state) {
                        'success' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('is_installment')
                    ->label('Installment')
                    ->alignCenter()
                    // ->badge()
                    ->color(fn(bool $state) => $state ? 'warning' : 'gray')
                    ->formatStateUsing(fn(bool $state) => $state ? 'Yes' : 'No')
                    ->extraAttributes([
                        // 'class' => 'px-2 py-0.5 text-xs rounded-md',
                         'class' => 'px-1.5 py-0.1 text-[10px] rounded',
                    ]),

                // TextColumn::make('payment_method')
                //     ->label('Method')
                //     ->toggleable(),

                // TextColumn::make('created_at')
                //     ->label('Paid At')
                //     ->dateTime()
                //     ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // ->exportable([
            //     'reference' => 'Reference',
            //     'booking.guest_name' => 'Guest',
            //     'booking.apartment.name' => 'Apartment',
            //     'amount' => 'Amount',
            //     'payment_method' => 'Method',
            //     'status' => 'Status',
            //     'created_at' => 'Date',
            // ])

            ->filters([
                SelectFilter::make('gateway')
                    ->options([
                        'paystack' => 'Paystack',
                        'wallet' => 'Wallet',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'success' => 'Successful',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ]),

                TernaryFilter::make('is_installment')
                    ->label('Installment'),
            ])

            ->recordActions([
                // Finance data should not be editable
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                ]),
            ]);
    }
}

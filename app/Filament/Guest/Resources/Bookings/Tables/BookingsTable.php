<?php

namespace App\Filament\Guest\Resources\Bookings\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Services\WalletService;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('No bookings found!')
            ->emptyStateDescription('You don\'t have bookings yet.')
            ->emptyStateActions([
                // CreateAction::make(),
            ])
            ->columns([
                TextColumn::make('apartment.name')
                    ->label('Apartment')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->visibleFrom('md'),

                TextColumn::make('end_date')
                    ->date(),

                TextColumn::make('amount')
                    ->money('NGN')
                    ->visibleFrom('md')
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                TextColumn::make('payments_sum_amount')
                    ->label('Paid')
                    ->default('0')
                    ->money('NGN')
                    ->sum('payments', 'amount')
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state) => match ($state) {
                        'paid' => 'heroicon-o-check-circle',
                        'pending' => 'heroicon-o-arrow-path',
                        'cancelled' => 'heroicon-o-x-circle',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActionsColumnLabel('Actions')
            ->recordActions([
                //
                // Action::make('pay')
                //     ->label('Pay Now')
                //     ->icon('heroicon-o-credit-card')
                //     ->color('success')
                //     ->visible(fn($record) => $record->status === 'pending')
                //     ->requiresConfirmation()
                //     ->url(fn($record) => route('bookings.pay', $record)),
                //added more
                Action::make('pay')
                    ->label('Pay Now')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->modalHeading('Make a Payment')
                    ->modalSubmitActionLabel('Proceed to Paystack')
                    ->modalWidth('sm')
                    ->visible(
                        fn($record) =>
                        $record->status === 'pending' && $record->balanceAmount() > 0
                    )
                    ->schema([
                        TextInput::make('amount')
                            ->label('Amount to Pay (₦)')
                            ->numeric()
                            ->required()
                            ->minValue(1000)
                            ->helperText(
                                fn($record) =>
                                'Balance: ₦' . number_format($record->balanceAmount())
                            )
                            ->rules([
                                fn($record) => function ($attribute, $value, $fail) use ($record) {
                                    if ($value > $record->balanceAmount()) {
                                        $fail('Amount cannot exceed outstanding balance.');
                                    }
                                },
                            ]),
                    ])
                    ->action(function ($record, array $data) {
                        return redirect()->route('bookings.pay', [
                            'booking' => $record->id,
                            'amount'  => $data['amount'],
                        ]);
                    }),

                Action::make('walletPay')
                    ->label('Pay with Wallet')
                    ->icon('heroicon-o-wallet')
                    ->color('gray')
                    ->modalHeading('Wallet Payment')
                    ->modalSubmitActionLabel('Confirm Payment')
                    ->modalWidth('sm')
                    ->visible(
                        fn($record) =>
                        // auth()->user()->wallet?->balance > 0 &&
                        Auth::user()->wallet?->balance > 0 &&
                            $record->status === 'pending' &&
                            $record->balanceAmount() > 0
                    )
                    ->schema([
                        TextInput::make('amount')
                            ->label('Amount (₦)')
                            ->numeric()
                            ->required()
                            ->minValue(100)
                            ->helperText(
                                fn($record) =>
                                'Wallet: ₦' . number_format(Auth::user()->wallet->balance)
                                    . ' | Balance: ₦' . number_format($record->balanceAmount())
                            )->rules([
                                fn($record) => function ($attribute, $value, $fail) use ($record) {
                                    if ($value > (Auth::user()?->wallet?->balance ?? 0)) {
                                        $fail('Insufficient wallet balance.');
                                    }
                                    if ($value > $record->balanceAmount()) {
                                        $fail('Amount exceeds booking balance.');
                                    }
                                },
                            ]),
                    ])
                    ->action(function ($record, array $data) {
                        WalletService::payBookingFromWallet(
                            booking: $record,
                            amount: (float) $data['amount'],
                            user: Auth::user()
                        );
                    }),

                // EditAction::make(),
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}

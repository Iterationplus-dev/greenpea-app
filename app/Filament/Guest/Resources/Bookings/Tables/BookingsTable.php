<?php

namespace App\Filament\Guest\Resources\Bookings\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Services\WalletService;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')

            /* ---------------------------------
             | EMPTY STATE
             |---------------------------------*/
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('No bookings found')
            ->emptyStateDescription('You have not made any bookings yet.')

            /* ---------------------------------
             | COLUMNS
             |---------------------------------*/
            ->columns([
                TextColumn::make('apartment.name')
                    ->label('Apartment')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('start_date')
                    ->label('Start')
                    ->date()
                    ->visibleFrom('md'),

                TextColumn::make('end_date')
                    ->label('End')
                    ->date(),

                TextColumn::make('amount')
                    ->label('Total')
                    ->money('NGN')
                    ->alignRight()
                    ->visibleFrom('md'),

                TextColumn::make('payments_sum_amount')
                    ->label('Payment')
                    ->money('NGN')
                    ->sum('payments', 'amount')
                    ->alignRight(),

                TextColumn::make('status')
                    ->badge()
                    ->alignCenter()
                    ->icon(fn(string $state) => match ($state) {
                        'approved' => 'heroicon-o-check-circle',
                        'pending' => 'heroicon-o-arrow-path',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn(string $state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])

            /* ---------------------------------
             | RECORD ACTIONS
             |---------------------------------*/
            ->recordActions([

                /* ===== PAY WITH PAYSTACK ===== */
                Action::make('pay')
                    ->label('Pay Now')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->modalHeading('Make a Payment')
                    ->modalSubmitActionLabel('Proceed to Paystack')
                    ->modalWidth('sm')
                    ->visible(
                        fn($record) =>
                        $record->status === 'pending'
                            && $record->balanceAmount() > 0
                    )
                    ->schema([
                        TextInput::make('amount')
                            ->label('Amount (₦)')
                            ->numeric()
                            ->required()
                            ->minValue(1000)
                            ->helperText(
                                fn($record) =>
                                'Outstanding balance: ₦' . number_format($record->balanceAmount())
                            )
                            ->rules([
                                fn($record) => function ($attribute, $value, $fail) use ($record) {
                                    if ($value > $record->balanceAmount()) {
                                        $fail('Amount cannot exceed outstanding balance.');
                                    }
                                },
                            ]),
                    ])
                    ->action(
                        fn($record, array $data) =>
                        redirect()->route('bookings.pay', [
                            'booking' => $record->id,
                            'amount'  => $data['amount'],
                        ])
                    ),

                /* ===== PAY WITH WALLET ===== */
                Action::make('walletPay')
                    ->label('Pay with Wallet')
                    ->icon('heroicon-o-wallet')
                    ->color('gray')
                    ->modalHeading('Wallet Payment')
                    ->modalSubmitActionLabel('Confirm Payment')
                    ->modalWidth('sm')
                    ->visible(
                        fn($record) =>
                        Auth::check()
                            && Auth::user()->wallet
                            && Auth::user()->wallet->balance > 0
                            && $record->status === 'pending'
                            && $record->balanceAmount() > 0
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
                            )
                            ->rules([
                                fn($record) => function ($attribute, $value, $fail) use ($record) {
                                    $walletBalance = Auth::user()?->wallet?->balance ?? 0;

                                    if ($value > $walletBalance) {
                                        $fail('Insufficient wallet balance.');
                                    }

                                    if ($value > $record->balanceAmount()) {
                                        $fail('Amount exceeds booking balance.');
                                    }
                                },
                            ]),
                    ])
                    ->action(
                        fn($record, array $data) =>
                        WalletService::payBookingFromWallet(
                            booking: $record,
                            amount: (float) $data['amount'],
                            user: Auth::user()
                        )
                    ),

                /* ===== DOWNLOAD INVOICE ===== */
                Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn($record) => $record->invoice?->pdf_url)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->invoice !== null),

                /* ===== VIEW ===== */
                // ViewAction::make(),
            ]);
    }
}

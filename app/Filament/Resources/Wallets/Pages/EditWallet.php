<?php

namespace App\Filament\Resources\Wallets\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Wallets\WalletResource;

class EditWallet extends EditRecord
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('credit')
                ->label('Credit Wallet')
                ->color('success')
                ->icon('heroicon-o-plus-circle')
                ->schema([
                    TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->minValue(1),

                    TextInput::make('description')
                        ->default('Manual admin credit'),
                ])
                ->action(function (array $data) {
                    $this->record->credit($data['amount'], $data['description']);

                    Notification::make()
                        ->title('Wallet credited')
                        ->success()
                        ->send();
                }),

            Action::make('debit')
                ->label('Debit Wallet')
                ->color('danger')
                ->icon('heroicon-o-minus-circle')
                ->schema([
                    TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->minValue(1),

                    TextInput::make('description')
                        ->default('Manual admin debit'),
                ])
                ->action(function (array $data) {
                    try {
                        $this->record->debit($data['amount'], $data['description']);

                        Notification::make()
                            ->title('Wallet debited')
                            ->success()
                            ->send();
                    } catch (\RuntimeException $e) {
                        Notification::make()
                            ->title('Insufficient balance')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}

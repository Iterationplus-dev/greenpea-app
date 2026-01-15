<?php

namespace App\Filament\Guest\Resources\Wallets\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Guest\Resources\Wallets\WalletResource;

class EditWallet extends EditRecord
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('fund')
                ->label('Fund Wallet')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    TextInput::make('amount')
                        ->numeric()
                        ->minValue(100)
                        ->required(),
                ])
                ->action(
                    fn(array $data) =>
                    redirect()->route('wallet.paystack.init', [
                        'amount' => $data['amount'],
                    ])
                ),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->refresh();

        return $data;
    }

    protected function getFormActions(): array
    {
        return [];
    }
}

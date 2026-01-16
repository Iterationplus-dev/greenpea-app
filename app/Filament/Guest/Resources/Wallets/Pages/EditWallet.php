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
                ->icon('heroicon-o-wallet')
                ->size('sm')
                ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                ->modalSubmitActionLabel('Fund Wallet')
                ->modalWidth('sm')
                ->schema([
                    TextInput::make('amount')
                        ->numeric()
                        ->minValue(5000)
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

    public function getTitle(): string
    {
        return "My Wallet";
    }
}

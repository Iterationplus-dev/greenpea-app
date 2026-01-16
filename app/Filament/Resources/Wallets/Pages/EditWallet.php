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
                ->size('sm')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('Credit Wallet')
                ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                ->schema([
                    TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->minValue(1),

                    TextInput::make('description')
                        ->default('Manual admin credit')
                        ->autoComplete(false),
                ])
                ->action(function (array $data) {
                    $this->record->credit(
                        $data['amount'],
                        $data['description']
                    );


                    Notification::make()
                        ->title('Wallet credited')
                        ->body('Wallet credited successfully!')
                        ->success()
                        ->send();

                    // 🔄 This reloads the page and all relation managers
                    $this->redirect(
                        static::getResource()::getUrl('edit', [
                            'record' => $this->record,
                        ])
                    );
                }),

            Action::make('debit')
                ->label('Debit Wallet')
                ->color('danger')
                ->icon('heroicon-o-minus-circle')
                ->size('sm')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('Debit Wallet')
                ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
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
                        $this->record->debit(
                            $data['amount'],
                            $data['description']
                        );


                        Notification::make()
                            ->title('Wallet debited')
                            ->body('Wallet debited successfully!')
                            ->success()
                            ->send();
                    } catch (\RuntimeException $e) {
                        Notification::make()
                            ->title('Insufficient balance')
                            ->body('Wallet Insufficient balance!')
                            ->danger()
                            ->send();
                    }

                    // 🔄 This reloads the page and all relation managers
                    $this->redirect(
                        static::getResource()::getUrl('edit', [
                            'record' => $this->record,
                        ])
                    );
                }),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return "Wallet";
    }
}

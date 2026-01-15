<?php

namespace App\Filament\Guest\Resources\Wallets\Pages;

use App\Filament\Guest\Resources\Wallets\WalletResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Models\Wallet;

class ListWallets extends ListRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        $wallet = Wallet::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $this->redirect(
            WalletResource::getUrl('edit', [
                'record' => $wallet,
            ])
        );
    }

    /**
     * Prevent table from rendering (failsafe)
     */
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [];
    }

    protected function hasTable(): bool
    {
        return false;
    }
}

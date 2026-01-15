<?php

namespace App\Filament\Guest\Resources\Wallets\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class WalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('My Wallet')
                ->description('Your current available wallet balance')
                ->schema([
                    TextInput::make('balance')
                        ->label('Wallet Balance')
                        ->prefix('₦')
                        ->disabled()
                        ->extraAttributes([
                            'class' => 'text-xl font-bold',
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}

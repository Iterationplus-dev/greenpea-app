<?php

namespace App\Filament\Resources\Wallets\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class TransactionsRelationManager extends RelationManager

{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Wallet Transactions';


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('wallet')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('wallet')
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'credit',
                        'danger' => 'debit',
                    ]),

                TextColumn::make('amount')
                    ->money('NGN')
                    ->weight('bold')
                    ->alignLeft()
                    ->extraAttributes(['class' => 'custom-padding-left-column']),

                TextColumn::make('reference')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}

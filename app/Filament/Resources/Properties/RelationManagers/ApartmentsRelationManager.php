<?php

namespace App\Filament\Resources\Properties\RelationManagers;

use Filament\Tables\Table;
use Forms\Components\TextInput;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Properties\PropertyResource;
use Filament\Resources\RelationManagers\RelationManager;

class ApartmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'apartments';
    protected static ?string $title = 'Apartments';

    protected static ?string $relatedResource = PropertyResource::class;



    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('monthly_price')
                    ->money('NGN'),

                IconColumn::make('is_available')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

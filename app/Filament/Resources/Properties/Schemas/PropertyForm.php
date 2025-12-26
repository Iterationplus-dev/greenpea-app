<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Property Information')
                    ->schema([
                        TextInput::make('Property name')
                            ->required()
                            ->maxLength(255),

                        Select::make('city')
                            ->options([
                                'Abuja' => 'Abuja',
                                'Lagos' => 'Lagos',
                                'Port Harcourt' => 'Port Harcourt',
                            ])
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Property Information')
                    ->schema([
                        TextInput::make('address')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                        Hidden::make('owner_id')
                            ->default(fn() => auth()->id())
                            ->visible(fn() => auth()->user()->isAdmin() === false),
                    ]),
                Section::make('Description')
                    ->schema([]),






            ]);
    }
}

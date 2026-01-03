<?php

namespace App\Filament\Resources\Apartments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Filament\Forms\Components\CloudinaryUpload;
use Filament\Forms\Components\RichEditor;

class ApartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make(null)
                    ->description('Add apartment basic details...')
                    // ->columns(1)
                    ->schema([
                        Select::make('property_id')
                            ->relationship('property', 'name')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                            ])
                            ->columnSpanFull(),


                    ])->columns(2),

                Section::make(null)
                    ->description('Add apartment price and image details...')
                    // ->columns(1)
                    ->schema([

                        TextInput::make('price_per_month')
                            ->numeric()
                            ->required()
                            ->prefix('₦')
                            ->columnSpanFull(),

                        Toggle::make('is_available')
                            ->default(true)
                            ->columnSpanFull(),

                        // FileUpload::make('images')
                        //     ->multiple()
                        //     ->directory('apartments')
                        //     ->image()
                        //     ->columnSpanFull(),
                        CloudinaryUpload::make('images')
                            ->label('Apartment Images')
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }
}

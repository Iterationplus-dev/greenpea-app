<?php

namespace App\Filament\Resources\Apartments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Forms\Components\CloudinaryUpload;

class ApartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make(null)
                    ->description('Add property details')
                    // ->columns(1)
                    ->schema([
                        Select::make('property_id')
                            ->relationship('property', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make(null)
                    ->description('Add apartment price and floor')
                    // ->columns(1)
                    ->schema([

                        TextInput::make('floor')
                            ->numeric()
                            ->nullable()
                            ->default('0')
                            ->columnSpanFull(),

                        TextInput::make('monthly_price')
                            ->numeric()
                            ->required()
                            ->prefix('₦')
                            ->columnSpanFull(),

                        // FileUpload::make('images')
                        //     ->multiple()
                        //     ->directory('apartments')
                        //     ->image()
                        //     ->columnSpanFull(),
                        // CloudinaryUpload::make('images')
                        //     ->label('Apartment Images')
                        //     ->dehydrated(false)   // VERY IMPORTANT
                        //     ->columnSpanFull(),

                    ])->columns(2),
                Section::make('Description & Photos')
                    ->description('You can add multiple images')
                    ->schema([

                        RichEditor::make('description')
                            ->label('Description')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                            ])
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'custom-rich-editor']),

                        FileUpload::make('images')
                            ->label('Photos')
                            ->multiple()
                            ->image()
                            ->disk('public')              // temp storage
                            ->directory('apartments/tmp') // temp folder
                            ->preserveFilenames()
                            ->reorderable(),

                        Toggle::make('is_available')
                            ->label('Apartment Status')
                            ->helperText('Toggle On/Off to indicate if this apartment is available or not')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }
}

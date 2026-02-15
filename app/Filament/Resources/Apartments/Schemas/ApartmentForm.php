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
use Illuminate\Support\HtmlString;
use League\Uri\Contracts\HostInterface;

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
                            ->reorderable()
                            ->maxSize(500) // 500 KB
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1024:700')
                            ->imageResizeTargetWidth(1024)
                            ->imageResizeTargetHeight(700)
                            ->rules(['dimensions:width=1024,height=700'])
                            ->helperText(new HtmlString('<span class="text-sm text-red-500">Upload images of the apartment. You can upload multiple images and reorder them as needed. Each image must be exactly 1024 x 700px and not exceed 500kb. Only jpg, jpeg, png formats are allowed.</span>'))

                            ->extraAttributes(['class' => 'text-danger'])
                            ->columnSpanFull()
                            ->hint('After uploading, the images will be processed and moved to permanent storage. Please wait a moment before trying to view them.'),

                        Toggle::make('is_available')
                            ->label('Apartment Status')
                            ->helperText('Toggle On/Off to indicate if this apartment is available or not')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }
}

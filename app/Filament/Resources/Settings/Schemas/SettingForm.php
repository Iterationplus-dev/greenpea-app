<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\View;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema(fn($record) => [
            Section::make('Setting')
                ->description('Edit this system configuration value')
                ->schema([
                    Grid::make(2)
                        ->schema([

                            // Left: Setting fields
                            Grid::make(1)
                                ->schema([
                                    TextInput::make('key')
                                        ->label('Setting Key')
                                        ->disabled()
                                        ->helperText(""),

                                    match ($record->type) {
                                        'bool' => Toggle::make('value')
                                            ->label('Value')
                                            ->helperText('this is helper')
                                            ->helperText("This must be a true/false value for the setting key above"),

                                        'int' => TextInput::make('value')
                                            ->label('Value')
                                            ->numeric()
                                            ->helperText("This must be a numeric value for the setting key above"),

                                        'json' => Textarea::make('value')
                                            ->label('Value')
                                            ->rows(6)
                                            ->helperText("This must be a json value for the setting key above"),

                                        default => TextInput::make('value')
                                            ->label('Value')
                                            ->helperText("This must be a text value for the setting key above"),
                                    },
                                ]),

                            // Right: Image panel
                            View::make('filament.settings.image')
                                ->columnSpan(1),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Amenities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class AmenityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(null)
                    ->description('Add amenity details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('icon')
                            ->label('Icon')
                            ->prefix('heroicon-')
                            ->placeholder('o-wifi')
                            ->helperText(new HtmlString('Enter the icon suffix, e.g. o-wifi, o-tv, o-sun. Browse icons at <a href="https://heroicons.com/" target="_blank" class="text-primary-600 underline">heroicons.com</a>'))
                            ->maxLength(100)
                            ->dehydrateStateUsing(function (?string $state) {
                                if (! $state) {
                                    return null;
                                }

                                return str_starts_with($state, 'heroicon-') ? $state : 'heroicon-' . $state;
                            })
                            ->afterStateHydrated(function (TextInput $component, ?string $state) {
                                if ($state && str_starts_with($state, 'heroicon-')) {
                                    $component->state(substr($state, 9));
                                }
                            })
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}

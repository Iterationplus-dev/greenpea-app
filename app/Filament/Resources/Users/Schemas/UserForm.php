<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\CheckboxList;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => bcrypt($state))
                            ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
                            ->hiddenOn('edit'),
                    ])->columns(2),

                    Section::make('Roles')
                ->schema([
                    CheckboxList::make('roles')
                        ->relationship('roles', 'name')
                        ->columns(2),
                ]),
            ]);
    }
}

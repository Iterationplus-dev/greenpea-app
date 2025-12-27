<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Filament\Schemas\Schema;
use function Laravel\Prompts\search;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\CheckboxList;

use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Users\Pages\CreateUser;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->description('Basic details of the user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('email')
                            ->email()
                            ->label('Email Address')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->label('Mobile No')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Roles & Security')
                    ->description('Assign roles to the user and security')
                    ->schema([
                        Select::make('role')
                            ->label('User Roles')
                            // ->multiple()
                            // ->options(array_combine(
                            //     array_map(fn($role) => $role->value, UserRole::cases()),
                            //     array_map(fn($role) => ucwords(str_replace('_', ' ', strtolower($role->value))), UserRole::cases())
                            // ))
                            ->options(
                                collect(UserRole::cases())
                                    ->reject(fn(UserRole $role) => in_array($role->value, ['super_admin', 'admin',]))
                                    ->mapWithKeys(fn($role) => [
                                        $role->value => ucfirst(str_replace('_', ' ', strtolower($role->value))),
                                    ])
                                    ->toArray()
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => bcrypt($state))
                            // ->required(fn($livewire) => $livewire instanceof CreateUser)
                            ->required(fn(string $context) => $context === 'create')
                            ->revealable()
                            ->columns(1)
                            ->hiddenOn('edit'),

                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->columns(1)
                            ->dehydrated(false),

                        Select::make('status')
                            ->label('Account Status')
                            // ->options(array_map(fn($status) => ucwords($status->value), UserStatus::cases()))
                            ->options(
                                collect(UserStatus::cases())
                                    ->mapWithKeys(fn($status) => [
                                        $status->value => ucfirst($status->value),
                                    ])
                                    ->toArray()
                            )
                            ->default(UserStatus::ACTIVE->value)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}

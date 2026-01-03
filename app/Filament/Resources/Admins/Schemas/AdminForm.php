<?php

namespace App\Filament\Resources\Admins\Schemas;

use App\Enums\AdminRole;
use App\Enums\AdminType;
use App\Enums\AdminStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Internal Users')
                    ->description('basic system users...')
                    // ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('phone')
                            ->tel(),

                        TextInput::make('password')
                            ->password()
                            ->required(fn($record) => $record === null)
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn($state) => filled($state)),

                        Select::make('role')
                            ->options(AdminRole::class)
                            ->required(),

                        Select::make('type')
                            ->options(AdminType::class)
                            ->required(),

                        Select::make('status')
                            ->options(AdminStatus::class)
                            ->required(),

                        Toggle::make('email_verified_at')
                            ->label('Email Verified')
                            ->onIcon('heroicon-o-check-circle')
                            ->offIcon('heroicon-o-x-circle'),

                    ]),
            ]);
    }
}

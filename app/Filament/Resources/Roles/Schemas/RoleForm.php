<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\CheckboxList;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make('Role Information')
                    ->schema([
                        //
                        TextInput::make('name')
                            ->required(),

                        CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->columns(3),
                    ])->columnSpanFull(),
            ]);
    }
}

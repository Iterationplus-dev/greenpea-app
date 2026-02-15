<?php

namespace App\Filament\Resources\Properties\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Property Information')
                    ->description('Basic details about the property')
                    ->columns(2)
                    ->schema([
                        Select::make('owner_id')
                            // ->label('Select Owner')
                            ->label('Property Owner')
                            ->hint('This field is set at default to GreenPea Apartments as owner of the property. Only visible to super admins.')
                            ->options(function () {
                                $firstOwner = User::query()
                                    ->where('role', UserRole::PROPERTY_OWNER->value)
                                    ->first();
                                return $firstOwner ? [$firstOwner->id => $firstOwner->name] : [];
                            })
                            ->default(function () {
                                $firstOwner = User::query()
                                    ->where('role', UserRole::PROPERTY_OWNER->value)
                                    ->first();
                                return $firstOwner ? $firstOwner->id : null;
                            })
                            ->disabled()
                            ->visible(fn() => auth()->user()->isSuper())
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Property Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Enter the name of the property. E.g "Sunset Apartments"')
                            ->placeholder('Property name')
                            ->autoComplete(false),

                        Select::make('city')
                            ->options([
                                'Abuja' => 'Abuja',
                                'Enugu' => 'Enugu',
                                'Lagos' => 'Lagos',
                                'Port Harcourt' => 'Port Harcourt',
                            ])
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select the city where the property is located. E.g "Lagos"')
                            ->placeholder('Select city...'),

                        TextInput::make('address')
                            ->required()
                            ->helperText('Enter the full address of the property.')
                            ->placeholder('Property address')
                            ->autoComplete(false),


                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->helperText('Set the property status to active or inactive'),
                        Hidden::make('owner_id')
                            ->default(fn() => auth()->id())
                            ->visible(fn() => auth()->user()->isSuper() === false),

                        RichEditor::make('description')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                ['attachFiles'],

                                ['undo', 'redo'],
                            ])
                            ->label('Description')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'custom-rich-editor-150'])
                            ->helperText('Provide a brief description of the property.')
                            ->placeholder('Enter property description...'),
                    ])->columnspanFull(),


            ]);
    }
}

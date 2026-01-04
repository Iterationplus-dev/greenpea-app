<?php

namespace App\Filament\Resources\Apartments\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use App\services\ApartmentImageService;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';
    protected static ?string $title = 'Apartment Images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('apartments/tmp')
                    ->required(),

                TextInput::make('alt_text')
                    ->label('Alt Text')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('url')
                    ->label('Image')
                    ->square()
                    ->imageSize(60),

                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->onColor('success')
                    ->offColor('gray')
                    ->extraAttributes(['class' => 'text-xs px-3 py-1.5']),
            ])
            // ->reorderable('sort_order')
            ->filters([
                //
            ])

            ->headerActions([
                CreateAction::make()
                    ->label("New Image")
                    ->size('sm')
                    ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                    ->icon('heroicon-o-plus'),
                AssociateAction::make()
                    ->size('sm')
                    ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                    ->icon('heroicon-o-link'),
            ])
            ->recordActions([
                ActionGroup::make([
                    CreateAction::make()
                        ->label('New Image')
                        ->icon('heroicon-o-plus')
                        ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                        ->using(function (array $data, $livewire) {
                            app(ApartmentImageService::class)
                                ->storeFromPaths(
                                    $livewire->ownerRecord->id,
                                    [$data['image']],
                                    $data['alt_text'] ?? null
                                );
                        }),
                    EditAction::make()
                        ->size('sm')
                        ->extraAttributes(['class' => 'text-xs px-3 py-1.5']),
                    DissociateAction::make()
                        ->size('sm')
                        ->extraAttributes(['class' => 'text-xs px-3 py-1.5']),
                    DeleteAction::make()
                        ->size('sm')
                        ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                        ->visible(fn($record) => ! $record->is_featured),
                ])
            ]);
            // ->toolbarActions([
            //     BulkActionGroup::make([
            //         DissociateBulkAction::make(),
            //         DeleteBulkAction::make(),
            //     ]),
            // ]);
    }
}

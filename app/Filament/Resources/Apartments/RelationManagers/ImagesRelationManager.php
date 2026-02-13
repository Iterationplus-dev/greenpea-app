<?php

namespace App\Filament\Resources\Apartments\RelationManagers;

use App\services\ApartmentImageService;
use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Apartment Images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Add Details')
                    ->schema([

                        Grid::make(1)
                            ->schema([

                                FileUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('apartments/tmp')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('alt_text')
                                    ->label('Alt Text')
                                    ->maxLength(255)
                                    ->columnSpan(1),

                            ]),

                    ])->columnSpanFull(),
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
                    ->label('New Image')
                    ->size('sm')
                    ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                    ->icon('heroicon-o-plus')
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Save Image')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Image Added')
                            ->body('The apartment image has been uploaded successfully.')
                    ),
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
                        ->createAnother(false)
                        ->modalSubmitActionLabel('Save Image')
                        ->using(function (array $data, $livewire) {
                            app(ApartmentImageService::class)
                                ->storeFromPaths(
                                    $livewire->ownerRecord->id,
                                    [$data['image']],
                                    $data['alt_text'] ?? null
                                );
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Image Added')
                                ->body('The apartment image has been uploaded successfully.')
                        ),
                    EditAction::make()
                        ->size('sm')
                        ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Image Updated')
                                ->body('The apartment image details have been updated.')
                        ),
                    DissociateAction::make()
                        ->size('sm')
                        ->extraAttributes(['class' => 'text-xs px-3 py-1.5']),
                    DeleteAction::make()
                        ->size('sm')
                        ->extraAttributes(['class' => 'text-xs px-3 py-1.5'])
                        ->visible(fn ($record) => ! $record->is_featured)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Image Deleted')
                                ->body('The apartment image has been deleted successfully.')
                        ),
                ]),
            ]);
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DissociateBulkAction::make(),
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}

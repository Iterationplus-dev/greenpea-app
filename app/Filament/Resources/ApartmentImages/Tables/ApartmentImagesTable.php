<?php

namespace App\Filament\Resources\ApartmentImages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ApartmentImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('url')
                    ->label('Image')
                    ->square()
                    ->extraImgAttributes([
                        'loading' => 'lazy',
                        'decoding' => 'async',
                    ])
                    ->imageSize(60),

                TextColumn::make('apartment.name')
                    ->label('Apartment')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('alt_text')
                    ->label('Alt Text')
                    ->placeholder('-')
                    ->limit(30),

                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->onColor('success')
                    ->offColor('gray')
                    ->alignCenter(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')

            ->emptyStateHeading('No Apartment Images')
            ->emptyStateDescription('Apartment images will appear here automatically.')
            ->emptyStateIcon('heroicon-o-photo')
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
                DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Image Deleted')
                            ->body('The apartment image has been deleted successfully.')
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Images Deleted')
                                ->body('The selected apartment images have been deleted successfully.')
                        ),
                ]),
            ]);
    }
}

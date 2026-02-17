<?php

namespace App\Filament\Resources\Apartments\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AmenitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'amenities';

    protected static ?string $title = 'Amenities';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Amenity')
                    ->sortable(),

                TextColumn::make('icon')
                    ->label('Icon'),
            ])
            ->filters([])
            ->headerActions([
                AttachAction::make()
                    ->label('Attach Amenity')
                    ->size('sm')
                    ->icon('heroicon-o-plus')
                    ->preloadRecordSelect()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Amenity Attached')
                            ->body('The amenity has been added to this apartment successfully.')
                    ),
            ])
            ->recordActions([
                DetachAction::make()
                    ->size('sm')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Amenity Removed')
                            ->body('The amenity has been removed from this apartment.')
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}

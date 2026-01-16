<?php

namespace App\Filament\Resources\Activities;

use App\Enums\GroupLabel;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Spatie\Activitylog\Models\Activity;
use App\Filament\Resources\Activities\Pages\ListActivities;
use App\Filament\Resources\Activities\Schemas\ActivityForm;
use App\Filament\Resources\Activities\Tables\ActivitiesTable;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|UnitEnum|null $navigationGroup = GroupLabel::SETTINGS;
    protected static ?string $navigationLabel = 'Activity Logs';
    protected static ?int $navigationSort = 9;

    protected static ?string $recordTitleAttribute = 'description';

    /**
     * Only Super Admins and Admins can see activity logs
     */
    public static function canAccess(): bool
    {
        return admin()?->isSuper() || admin()?->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return ActivityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivitiesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivities::route('/'),
        ];
    }
}

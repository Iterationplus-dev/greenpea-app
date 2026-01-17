<?php

namespace App\Filament\Resources\Permissions;

use UnitEnum;
use BackedEnum;
use App\Enums\GroupLabel;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Permission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Permissions\Tables\PermissionsTable;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;
    protected static string|UnitEnum|null $navigationGroup = GroupLabel::SETTINGS;
    protected static ?string $navigationLabel = 'Manage Permissions';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 4;

    /**
     * Only Super Admins can manage permissions
     */
    public static function canAccess(): bool
    {
        return admin()?->isSuper() === true;
    }

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit'   => EditPermission::route('/{record}/edit'),
        ];
    }
}

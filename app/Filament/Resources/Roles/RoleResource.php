<?php

namespace App\Filament\Resources\Roles;

use UnitEnum;
use BackedEnum;
use App\Enums\UserRole;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;

class RoleResource extends Resource
{
    // protected static ?string $model = Role::class;
    protected static ?string $model = Role::class;
    protected static string | UnitEnum | null $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Manage Roles';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $recordTitleAttribute = 'Role';

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole([UserRole::SUPER_ADMIN->value]);
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}

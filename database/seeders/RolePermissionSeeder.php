<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Create Roles and Permissions here using Spatie Permission package
        $permissions = [
            'properties.view',
            'properties.create',
            'properties.update',
            'properties.delete',
            'apartments.manage',
            'bookings.view',
            'bookings.approve',
            'bookings.refund',
            'payments.view',
            'payments.refund',
            'earnings.view',
            'settings.manage',
            'users.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $staff = Role::firstOrCreate(['name' => 'staff']);

        $admin->givePermissionTo(Permission::all());

        $owner->givePermissionTo([
            'properties.view',
            'properties.create',
            'properties.update',
            'apartments.manage',
            'bookings.view',
            'earnings.view',
        ]);

        $staff->givePermissionTo([
            'bookings.view',
            'bookings.approve',
        ]);
    }
}

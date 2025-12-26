<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@greenpeaapartments.com'],
            [
                'name' => 'Super Admin',
                'phone' => '+2348034567890',
                'password' => Hash::make('password'),
                'role' => UserRole::SUPER_ADMIN,
            ]
        );

        User::factory(5)->create([
            'role' => UserRole::PROPERTY_OWNER,
        ]);

        User::factory(1)->create([
            'role' => UserRole::ADMIN,
        ]);
        User::factory(1)->create([
            'role' => UserRole::MANAGER,
        ]);

        User::factory(10)->create([
            'role' => UserRole::GUEST,
        ]);

        User::factory(5)->create([
            'role' => UserRole::CUSTOMER,
        ]);
        User::factory(8)->create([
            'role' => UserRole::AGENT,
        ]);
        User::factory(3)->create([
            'role' => UserRole::STAFF,
        ]);
        User::factory(2)->create([
            'role' => UserRole::DEVELOPER,
        ]);
        User::factory(5)->create([
            'role' => UserRole::MARKETER,
        ]);
    }
}

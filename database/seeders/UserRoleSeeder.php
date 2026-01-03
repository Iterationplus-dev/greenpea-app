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
            ['email' => 'guest@greenpeaapartments.com'],
            [
                'name' => 'Super Guest',
                'phone' => '+2348034567890',
                'password' => Hash::make('password'),
                'role' => UserRole::GUEST,
            ]
        );

        User::factory(3)->create([
            'role' => UserRole::PROPERTY_OWNER,
        ]);

        User::factory(20)->create([
            'role' => UserRole::GUEST,
        ]);

        User::factory(2)->create([
            'role' => UserRole::CUSTOMER,
        ]);
        User::factory(5)->create([
            'role' => UserRole::AGENT,
        ]);

        User::factory(2)->create([
            'role' => UserRole::DEVELOPER,
        ]);
        User::factory(5)->create([
            'role' => UserRole::MARKETER,
        ]);
    }
}

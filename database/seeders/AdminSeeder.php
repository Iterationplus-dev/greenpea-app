<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Enums\AdminRole;
use App\Enums\AdminType;
use App\Enums\AdminStatus;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {

        Admin::updateOrCreate(
            ['email' => 'ceo@greenpeaapartments.com'],
            [
                'name' => 'GreenPea CEO',
                'phone' => '08030000001',
                'address' => 'GreenPea HQ',
                'password' => Hash::make('password'),
                'role' => AdminRole::SUPER_ADMIN,
                'type' => AdminType::CEO,
                'status' => AdminStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'admin@greenpeaapartments.com'],
            [
                'name' => 'GreenPea Admin',
                'phone' => '08030000002',
                'address' => fake()->address,
                'password' => Hash::make('password'),
                'role' => AdminRole::ADMIN,
                'type' => AdminType::ADMIN,
                'status' => AdminStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'manager@greenpeaapartments.com'],
            [
                'name' => 'System Manager',
                'phone' => '08030000003',
                'address' => fake()->address,
                'password' => Hash::make('password'),
                'role' => AdminRole::ADMIN,
                'type' => AdminType::MANAGER,
                'status' => AdminStatus::ACTIVE,
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'staff@greenpeaapartments.com'],
            [
                'name' => 'Front-desk Staff',
                'phone' => '08030000004',
                'address' => fake()->address,
                'password' => Hash::make('password'),
                'role' => AdminRole::ADMIN,
                'type' => AdminType::STAFF,
                'status' => AdminStatus::ACTIVE,
            ]
        );
    }
}

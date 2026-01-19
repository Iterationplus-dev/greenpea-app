<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlatformWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platformUser = User::firstOrCreate(
            ['email' => 'platform@system.local'],
            ['name' => 'Platform System', 'password' => bcrypt('system')]
        );

        $platformUser->wallet()->firstOrCreate([]);
    }
}

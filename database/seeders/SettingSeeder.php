<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::set('platform_fee_percentage', 10, 'int');
        Setting::set('currency', 'NGN');
        Setting::set('booking_grace_days', 2, 'int');
        Setting::set('allow_partial_payments', true, 'bool');
        Setting::set('system_name', 'Greenpea Apartments');
        Setting::set('contacts', '{"phone": "08038292919","mobile": "09029939933","email": "info@greenpeaapartments.com","address": "12 new avenue"}', 'json');
         Setting::set('socials', '{"facebook": "greenpea","instagram": "greenpea","linkedin": "greenpea","twitter": "@greenpea"}', 'json');
    }
}

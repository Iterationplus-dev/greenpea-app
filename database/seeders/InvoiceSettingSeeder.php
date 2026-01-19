<?php

namespace Database\Seeders;

use App\Models\InvoiceSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvoiceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvoiceSetting::firstOrCreate([
            'id' => 1,
        ], [
            'reminders_enabled' => true,
            'days_before_reminder' => 3,
            'reminder_interval_days' => 2,
            'max_reminders' => 3,
            'admin_email' => config('mail.admin_address'),
        ]);
    }
}

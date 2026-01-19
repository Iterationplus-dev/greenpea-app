<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $table = 'invoice_settings';
    protected $fillable = [
        'reminders_enabled',
        'days_before_reminder',
        'reminder_interval_days',
        'max_reminders',
        'admin_email',
    ];
}

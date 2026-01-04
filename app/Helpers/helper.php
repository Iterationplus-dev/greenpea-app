<?php

use App\Models\Admin;
use App\Models\Setting;
use Filament\Notifications\Notification;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('ownerId')) {
    function ownerId(): string
    {
        return 'owner_id';
    }
}


// if (! function_exists('admin')) {
//     function admin(): ?Admin
//     {
//         return auth('admin')->user();
//     }
// }

if (! function_exists('admin')) {
    function admin(): ?Admin
    {
        $user = auth('admin')->user();
        return $user instanceof Admin ? $user : null;
    }
}

if (! function_exists('tableDeleteRecordNotification')) {
    function tableDeleteRecordNotification(string $title): void
    {
        Notification::make()
            ->success()
            ->title($title . ' Deleted')
            ->body($title . ' details deleted successfully');
    }
}

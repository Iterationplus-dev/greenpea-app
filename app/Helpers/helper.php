<?php

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('platformFee')) {
    function platformFee(float $amount)
    {
        $grossAmount = $amount;

        $platformFee = round(
            $grossAmount * (setting('platform_fee_percentage') / 100),
            2
        );

        return $platformFee;
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


if (! function_exists('walletReference')) {
    function walletReference(): string
    {
        return 'WL_' . Str::uuid();
    }
}

if (! function_exists('bookingReference')) {
    function bookingReference(): string
    {
        return 'BKG-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
    }
}

if (! function_exists('paymentReference')) {
    function paymentReference(): string
    {
        return 'BK_' . Str::uuid();
    }
}

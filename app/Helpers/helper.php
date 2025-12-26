<?php

use App\Models\Setting;

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

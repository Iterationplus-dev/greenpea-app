<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $table = 'settings';
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting
                ? static::castValue($setting->value, $setting->type)
                : $default;
        });
    }

    public static function set(string $key, $value, string $type = 'string'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        Cache::forget("setting.$key");
    }

    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'int' => (int) $value,
            'bool' => (bool) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}

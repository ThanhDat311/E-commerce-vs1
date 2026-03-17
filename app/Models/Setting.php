<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Get a setting value by key.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::rememberForever("settings.{$key}", function () use ($key) {
            return static::where('key', $key)->first(['value', 'type']);
        });

        if (! $setting instanceof \App\Models\Setting) {
            return $default;
        }

        $value = $setting->value;

        if ($setting->type === 'json' || $setting->type === 'array') {
            return json_decode($value, true) ?: [];
        }

        if ($setting->type === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        if ($setting->type === 'integer' || $setting->type === 'int') {
            return (int) $value;
        }

        return $value;
    }

    /**
     * Set a setting value by key.
     *
     * @param  mixed  $value
     * @return void
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text')
    {
        $finalValue = $value;

        if (is_array($value) || is_object($value) || $type === 'json') {
            $finalValue = json_encode($value);
            $type = 'json';
        }

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $finalValue,
                'group' => $group,
                'type' => $type,
            ]
        );

        Cache::forget("settings.{$key}");
    }
}

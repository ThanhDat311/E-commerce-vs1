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
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $value = Cache::rememberForever("settings.{$key}", function () use ($key) {
            return static::where('key', $key)->value('value');
        });

        if (is_null($value)) {
            return $default;
        }

        // Auto-decode JSON or Boolean if needed? For now straightforward.
        // If type is boolean/json we could cast, but value is text.

        return $value;
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @return void
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text')
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
            ]
        );

        Cache::forget("settings.{$key}");
    }
}

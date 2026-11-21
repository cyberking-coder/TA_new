<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Simple key/value runtime settings — changed by admins in Filament, read
 * everywhere else. Values cached for 60s so hot paths don't hit the DB.
 */
class AppSetting extends Model
{
    protected $fillable = ['key', 'value', 'updated_by'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("app_setting:$key", 60, function () use ($key, $default) {
            $row = static::query()->where('key', $key)->value('value');
            return $row ?? $default;
        });
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $v = static::get($key, $default ? '1' : '0');
        return in_array(strtolower((string) $v), ['1', 'true', 'yes', 'on'], true);
    }

    public static function set(string $key, mixed $value, ?int $userId = null): void
    {
        $row = static::query()->firstOrNew(['key' => $key]);
        $row->value = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
        if ($userId) $row->updated_by = $userId;
        $row->save();
        Cache::forget("app_setting:$key");
    }
}

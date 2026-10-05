<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     * Gunakan cache agar tidak hit DB setiap request.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set / update nilai setting.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("site_setting_{$key}");
    }

    /**
     * Ambil semua setting sebagai array key => value.
     */
    public static function all_settings(): array
    {
        return Cache::remember('site_settings_all', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Hapus semua cache setting.
     */
    public static function clearAllCache(): void
    {
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("site_setting_{$key}");
        }
        Cache::forget('site_settings_all');
    }
}

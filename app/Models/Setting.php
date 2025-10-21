<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Mendapatkan nilai pengaturan berdasarkan kunci
     */
    public static function get($key, $default = null)
    {
        $setting = Cache::remember("setting.{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        return $setting ? $setting->value : $default;
    }

    /**
     * Menyimpan atau memperbarui pengaturan
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );

        // Clear cache
        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Mendapatkan semua pengaturan toko
     */
    public static function getStoreSettings()
    {
        return [
            'store_name' => self::get('store_name', 'Kafe Saya'),
            'store_logo' => self::get('store_logo'),
            'store_description' => self::get('store_description', 'Selamat datang di kafe kami'),
            'store_address' => self::get('store_address'),
            'store_phone' => self::get('store_phone'),
            'store_email' => self::get('store_email'),
        ];
    }

    /**
     * Mendapatkan URL logo toko
     */
    public static function getStoreLogoUrl()
    {
        $logoPath = self::get('store_logo');
        
        if (empty($logoPath)) {
            return null;
        }

        // Cek apakah file benar-benar ada
        if (file_exists(public_path('storage/' . $logoPath))) {
            return asset('storage/' . $logoPath);
        }

        return null;
    }
}

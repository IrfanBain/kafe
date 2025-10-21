<?php

namespace App\Providers;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Setting; // Pastikan path ini benar
use Illuminate\Support\Facades\URL; // Jika belum ada

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\URL::forceRootUrl(config(key: 'app.url'));

        if (str_contains(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Share store settings with all views
       $storeSettings = []; // Definisikan variabel default

    try {
        // Cek dulu apakah tabel 'settings' ada
        if (Schema::hasTable('settings')) {
            // Ambil pengaturan dari cache selamanya.
            $storeSettings = Cache::rememberForever('store_settings_global', function () {
                Log::info('Fetching store settings from database and caching.'); // Logging (opsional)
                // Panggil method model Anda untuk mengambil data
                return Setting::getStoreSettings();
            });
        } else {
            // Fallback jika tabel belum ada
            $storeSettings = $this->getFallbackSettings();
        }
    } catch (\Exception $e) {
        // Tangani error jika query gagal
        Log::error('Failed to load store settings: ' . $e->getMessage());
        $storeSettings = $this->getFallbackSettings();
    }

    // Pastikan $storeSettings selalu berupa array
    if (!is_array($storeSettings)) {
        Log::warning('Store settings retrieved were not an array. Using fallback.');
        $storeSettings = $this->getFallbackSettings();
    }

    // Bagikan data $storeSettings ke semua view (hanya sekali)
    View::share('storeSettings', $storeSettings);
      
}

protected function getFallbackSettings(): array
{
    return [
        'store_name' => config('app.name', 'Kafe'),
        'store_logo' => null,
        'store_description' => 'Selamat datang di kafe kami',
        'store_address' => null,
        'store_phone' => null,
        'store_email' => null,
    ];
}

}
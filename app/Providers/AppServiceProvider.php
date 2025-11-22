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
        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));

        if (str_contains(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $storeSettings = [];

        try {
            // LANGSUNG AMBIL DARI CACHE/DB, GAK USAH TANYA TABEL ADA/ENGGAK
            // KARENA KALAU GAGAL, DIA AKAN LARI KE 'CATCH' DI BAWAH
            $storeSettings = Cache::rememberForever('store_settings_global', function () {
                return Setting::getStoreSettings();
            });
        } catch (\Exception $e) {
            // Kalau error (misal tabel belum ada), pakai default
            $storeSettings = $this->getFallbackSettings();
        }

        if (!is_array($storeSettings)) {
            $storeSettings = $this->getFallbackSettings();
        }

        View::share('storeSettings', $storeSettings);
    }

protected function getFallbackSettings(): array
{
    return [
        'store_name' => config('app.name', 'Kafeee'),
        'store_logo' => null,
        'store_description' => 'Selamat datang di kafe kami',
        'store_address' => null,
        'store_phone' => null,
        'store_email' => null,
    ];
}

}
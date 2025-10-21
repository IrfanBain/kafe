<?php

namespace App\Filament\Cashier\Widgets;

use App\Models\Setting;
use Filament\Widgets\Widget;

class StoreInfoWidget extends Widget
{
    protected static string $view = 'filament.cashier.widgets.store-info';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getStoreInfo(): array
    {
        return [
            'name' => Setting::get('store_name', 'Kafe Digital'),
            'description' => Setting::get('store_description', 'Sistem POS Kafe Digital'),
            'address' => Setting::get('store_address'),
            'phone' => Setting::get('store_phone'),
            'email' => Setting::get('store_email'),
            'logo_url' => Setting::getStoreLogoUrl(),
        ];
    }
}
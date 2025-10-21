<?php

namespace App\Filament\Cashier\Resources\ProductResource\Pages;

use App\Filament\Cashier\Resources\ProductResource;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit action since editing should be done in admin panel
        ];
    }
}

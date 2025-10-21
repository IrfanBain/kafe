<?php

namespace App\Filament\Cashier\Resources\ProductResource\Pages;

use App\Filament\Cashier\Resources\ProductResource;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action since creation should be done in admin panel
        ];
    }
}

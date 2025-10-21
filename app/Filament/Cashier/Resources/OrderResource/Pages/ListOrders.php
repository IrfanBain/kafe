<?php

namespace App\Filament\Cashier\Resources\OrderResource\Pages;

use App\Filament\Cashier\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create action will be handled by separate page
        ];
    }
}

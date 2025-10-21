<?php

namespace App\Filament\Admin\Resources\CashierOrderResource\Pages;

use App\Filament\Admin\Resources\CashierOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashierOrders extends ListRecords
{
    protected static string $resource = CashierOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Cashier\Resources\TableResource\Pages;

use App\Filament\Cashier\Resources\TableResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTable extends ViewRecord
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Limited actions for cashier
        ];
    }
}

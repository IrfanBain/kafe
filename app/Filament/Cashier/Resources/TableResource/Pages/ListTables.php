<?php

namespace App\Filament\Cashier\Resources\TableResource\Pages;

use App\Filament\Cashier\Resources\TableResource;
use Filament\Resources\Pages\ListRecords;

class ListTables extends ListRecords
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action since creation should be done in admin panel
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\CashierOrderResource\Pages;

use App\Filament\Admin\Resources\CashierOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashierOrder extends EditRecord
{
    protected static string $resource = CashierOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

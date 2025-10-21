<?php

namespace App\Filament\Cashier\Pages;

use App\Filament\Cashier\Widgets\CashierStatsWidget;
use App\Filament\Cashier\Widgets\QuickActionsWidget;
use App\Filament\Cashier\Widgets\RecentOrdersWidget;
use App\Filament\Cashier\Widgets\StoreInfoWidget;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Cashier Dashboard';

    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            CashierStatsWidget::class,
            StoreInfoWidget::class,
            RecentOrdersWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 3,
            'lg' => 4,
            'xl' => 4,
        ];
    }
}

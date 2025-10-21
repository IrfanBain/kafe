<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Admin';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getColumns(): int|string|array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\SalesOverviewWidget::class,
            \App\Filament\Admin\Widgets\SalesChartWidget::class,
            \App\Filament\Admin\Widgets\TableStatusWidget::class,
            \App\Filament\Admin\Widgets\RevenueByCategoryWidget::class,
        ];
    }
}

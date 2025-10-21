<?php

namespace App\Filament\Cashier\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CashierStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = today();

        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedToday = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        return [
            Stat::make('Today Orders', $todayOrders)
                ->description('Total orders today')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Today Revenue', 'Rp ' . number_format($todayRevenue))
                ->description('Revenue from paid orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Orders waiting to be processed')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Completed Today', $completedToday)
                ->description('Orders completed today')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}

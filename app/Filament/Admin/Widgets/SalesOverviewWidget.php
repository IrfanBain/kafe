<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Today's stats
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Transaction::whereDate('created_at', today())
            ->where('status', 'success')
            ->sum('amount');

        // This month's stats
        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthlyRevenue = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'success')
            ->sum('amount');

        // Yesterday comparison
        $yesterdayOrders = Order::whereDate('created_at', Carbon::yesterday())->count();
        $yesterdayRevenue = Transaction::whereDate('created_at', Carbon::yesterday())
            ->where('status', 'success')
            ->sum('amount');

        // Calculate percentage changes
        $orderChange = $yesterdayOrders > 0 ?
            round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1) :
            ($todayOrders > 0 ? 100 : 0);

        $revenueChange = $yesterdayRevenue > 0 ?
            round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) :
            ($todayRevenue > 0 ? 100 : 0);

        // Active orders
        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])->count();

        // Average order value
        $avgOrderValue = $todayOrders > 0 ? $todayRevenue / $todayOrders : 0;

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description($revenueChange >= 0 ? "+{$revenueChange}% dari kemarin" : "{$revenueChange}% dari kemarin")
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueChange >= 0 ? 'success' : 'danger')
                ->chart([7, 12, 8, 15, 22, 18, $todayRevenue / 10000]),

            Stat::make('Order Hari Ini', $todayOrders)
                ->description($orderChange >= 0 ? "+{$orderChange}% dari kemarin" : "{$orderChange}% dari kemarin")
                ->descriptionIcon($orderChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($orderChange >= 0 ? 'success' : 'danger'),

            Stat::make('Order Aktif', $activeOrders)
                ->description('Sedang diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Rata-rata Order', 'Rp ' . number_format($avgOrderValue, 0, ',', '.'))
                ->description('Per transaksi hari ini')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make('Penjualan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description($monthlyOrders . ' total orders')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Penjualan 7 Hari Terakhir';

    protected static ?int $sort = 2;

    public ?string $filter = 'week';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        if ($this->filter === 'week') {
            // Get data for last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M j');

                $revenue = Transaction::whereDate('created_at', $date)
                    ->where('status', 'success')
                    ->sum('amount');

                $data[] = $revenue;
            }
        } elseif ($this->filter === 'month') {
            // Get data for last 30 days grouped by week
            for ($i = 3; $i >= 0; $i--) {
                $startDate = Carbon::now()->subWeeks($i)->startOfWeek();
                $endDate = Carbon::now()->subWeeks($i)->endOfWeek();

                $labels[] = $startDate->format('M j') . ' - ' . $endDate->format('M j');

                $revenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'success')
                    ->sum('amount');

                $data[] = $revenue;
            }
        } else { // year
            // Get data for last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M Y');

                $revenue = Transaction::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->where('status', 'success')
                    ->sum('amount');

                $data[] = $revenue;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan (Rp)',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => '7 Hari',
            'month' => '4 Minggu',
            'year' => '12 Bulan',
        ];
    }
}

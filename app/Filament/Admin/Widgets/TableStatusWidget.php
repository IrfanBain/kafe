<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Table;
use Filament\Widgets\ChartWidget;

class TableStatusWidget extends ChartWidget
{
    protected static ?string $heading = 'Status Meja';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $available = Table::where('status', 'available')->count();
        $occupied = Table::where('status', 'occupied')->count();
        $reserved = Table::where('status', 'reserved')->count();
        $maintenance = Table::where('status', 'maintenance')->count();

        return [
            'datasets' => [
                [
                    'data' => [$available, $occupied, $reserved, $maintenance],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // green for available
                        'rgb(239, 68, 68)',   // red for occupied
                        'rgb(251, 191, 36)',  // yellow for reserved
                        'rgb(107, 114, 128)', // gray for maintenance
                    ],
                ],
            ],
            'labels' => ['Tersedia', 'Terisi', 'Reservasi', 'Maintenance'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}

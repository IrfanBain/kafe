<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Category;
use App\Models\OrderItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueByCategoryWidget extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan per Kategori (30 Hari)';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $categories = Category::withCount(['products'])
            ->having('products_count', '>', 0)
            ->get();

        $data = [];
        $labels = [];
        $colors = [
            'rgb(59, 130, 246)',   // blue
            'rgb(16, 185, 129)',   // emerald
            'rgb(245, 101, 101)',  // red
            'rgb(251, 191, 36)',   // amber
            'rgb(139, 92, 246)',   // violet
            'rgb(236, 72, 153)',   // pink
            'rgb(34, 197, 94)',    // green
            'rgb(249, 115, 22)',   // orange
        ];

        foreach ($categories as $index => $category) {
            $revenue = OrderItem::whereHas('product', function ($query) use ($category) {
                    $query->where('category_id', $category->id);
                })
                ->whereHas('order', function ($query) {
                    $query->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                          ->whereNotIn('status', ['cancelled']);
                })
                ->sum('total');

            if ($revenue > 0) {
                $labels[] = $category->name;
                $data[] = $revenue;
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
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

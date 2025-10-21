<?php

namespace App\Filament\Cashier\Widgets;

use Filament\Actions\Action;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.cashier.widgets.quick-actions';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'md' => 'full',
        'lg' => 'full',
    ];

    protected static ?int $sort = 0;

    public function getActions(): array
    {
        return [
            Action::make('create_order')
                ->label('Create New Order')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(route('cashier.create-order'))
                ->openUrlInNewTab(false),
                
            Action::make('order_history')
                ->label('View Order History')
                ->icon('heroicon-o-clock')
                ->color('secondary')
                ->url(route('cashier.order-history'))
                ->openUrlInNewTab(false),
                
            Action::make('pos_dashboard')
                ->label('POS Dashboard')
                ->icon('heroicon-o-computer-desktop')
                ->color('success')
                ->url(route('cashier.dashboard'))
                ->openUrlInNewTab(false),
        ];
    }
}
<?php

namespace App\Filament\Cashier\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Orders';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'md' => 'full',
        'lg' => 'full',
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('created_by_cashier', true)
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('table.number')
                    ->label('Table')
                    ->default('Take Away'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'primary' => 'confirmed',
                        'warning' => 'preparing',
                        'info' => 'ready',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        \Filament\Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'preparing' => 'Preparing',
                                'ready' => 'Ready',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $oldStatus = $record->status;
                        $record->updateOrderStatus($data['status']);

                        \Filament\Notifications\Notification::make()
                            ->title('Status Updated')
                            ->body("Order #{$record->order_number} status changed from {$oldStatus} to {$data['status']}")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}

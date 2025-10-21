<?php

namespace App\Filament\Cashier\Resources;

use App\Filament\Cashier\Resources\OrderResource\Pages;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table as TableModel;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->maxLength(255),
                        Forms\Components\Select::make('order_type')
                            ->options([
                                'dine_in' => 'Dine In',
                                'take_away' => 'Take Away',
                            ])
                            ->required()
                            ->reactive(),
                        Forms\Components\Select::make('table_id')
                            ->label('Table')
                            ->options(TableModel::all()->pluck('number', 'id'))
                            ->visible(fn (Forms\Get $get) => $get('order_type') === 'dine_in'),
                    ])->columns(2),

                Forms\Components\Section::make('Order Items')
                    ->schema([
                        Forms\Components\Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            $set('price', $product->price);
                                        }
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $price = $get('price') ?? 0;
                                        $set('subtotal', $price * $state);
                                    }),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $quantity = $get('quantity') ?? 1;
                                        $set('subtotal', $state * $quantity);
                                    }),
                                Forms\Components\TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->readonly(),
                                Forms\Components\Textarea::make('notes')
                                    ->rows(2),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => Product::find($state['product_id'])?->name ?? null)
                            ->addActionLabel('Add Item')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Payment')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->readonly(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable()
                    ->default('Guest'),
                Tables\Columns\BadgeColumn::make('order_type')
                    ->colors([
                        'primary' => 'dine_in',
                        'secondary' => 'take_away',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('table.number')
                    ->label('Table')
                    ->default('Take Away'),
                Tables\Columns\BadgeColumn::make('created_by_cashier')
                    ->label('Source')
                    ->colors([
                        'success' => true,
                        'info' => false,
                    ])
                    ->formatStateUsing(fn (?bool $state): string => $state ? 'Cashier' : 'QR Code'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'primary' => 'confirmed',
                        'warning' => 'preparing',
                        'info' => 'ready',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready' => 'Ready',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('order_type')
                    ->options([
                        'dine_in' => 'Dine In',
                        'take_away' => 'Take Away',
                    ]),
                Tables\Filters\SelectFilter::make('created_by_cashier')
                    ->label('Source')
                    ->options([
                        '1' => 'Cashier',
                        '0' => 'QR Code',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['value'])) {
                            return $query->where('created_by_cashier', (bool) $data['value']);
                        }
                        return $query;
                    }),
                Tables\Filters\Filter::make('today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today()))
                    ->label('Today Orders'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Forms\Components\Select::make('status')
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
                            ->body("Order status changed from {$oldStatus} to {$data['status']}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('receipt')
                    ->label('Receipt')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Order $record): string => route('cashier.order.receipt', $record->order_number))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsCompleted')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $completedCount = 0;
                            $records->each(function ($record) use (&$completedCount) {
                                $record->updateOrderStatus('completed');
                                $completedCount++;
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Orders Completed')
                                ->body("{$completedCount} orders marked as completed")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Show all orders (both created by cashier and from QR code/table ordering)
        return parent::getEloquentQuery();
    }
}

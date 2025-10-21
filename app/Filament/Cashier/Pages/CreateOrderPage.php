<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table as TableModel;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Support\Exceptions\Halt;
use Midtrans\Config;
use Midtrans\Snap;

class CreateOrderPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';

    protected static string $view = 'filament.cashier.pages.create-order-page';

    protected static ?string $navigationLabel = 'Create Order';

    protected static ?string $title = 'Create New Order';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
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
                            ->live(),
                        Forms\Components\Select::make('table_id')
                            ->label('Table')
                            ->options(function () {
                                return TableModel::all()->pluck('number', 'id')->toArray();
                            })
                            ->visible(fn (Forms\Get $get) => $get('order_type') === 'dine_in'),
                    ])->columns(2),

                Forms\Components\Section::make('Order Items')
                    ->schema([
                        Forms\Components\Repeater::make('order_items')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(function () {
                                        return Product::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('price', $product->price);
                                                $set('total', $product->price);
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $price = floatval($get('price') ?? 0);
                                        $quantity = intval($state ?? 1);
                                        $set('total', $price * $quantity);
                                    }),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->readOnly(),
                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->readOnly(),
                                Forms\Components\Textarea::make('notes')
                                    ->rows(2),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                if (isset($state['product_id']) && is_numeric($state['product_id'])) {
                                    $product = Product::find($state['product_id']);
                                    return $product ? $product->name : 'Item';
                                }
                                return 'Item';
                            })
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
                    ]),
            ])
            ->statePath('data');
    }

    public function create()
    {
        try {
            $data = $this->form->getState();
            // Debug: dump data ke log
            Log::info('CreateOrderPage data', ['data' => $data]);

            // ...existing code...
            // (Paste the rest of your create logic here, pastikan tidak ada perubahan selain penambahan Log di atas)

            // Validate required data
            if (empty($data['order_items']) || count($data['order_items']) === 0) {
                throw new \Exception('At least one order item is required');
            }

            // Calculate total
            $total = 0;
            foreach ($data['order_items'] as $item) {
                if (!isset($item['total']) || $item['total'] <= 0) {
                    throw new \Exception('Invalid item total');
                }
                $total += $item['total'];
            }

            if ($total <= 0) {
                throw new \Exception('Order total must be greater than 0');
            }

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'table_id' => ($data['order_type'] === 'dine_in' && isset($data['table_id'])) ? $data['table_id'] : null,
                'customer_name' => $data['customer_name'] ?? null,
                'order_type' => $data['order_type'],
                'total_amount' => $total,
                'status' => ($data['payment_method'] === 'cash') ? 'confirmed' : 'pending',
                'payment_status' => ($data['payment_method'] === 'cash') ? 'paid' : 'pending',
                'created_by_cashier' => true,
            ]);

            if (!$order) {
                throw new \Exception('Failed to create order');
            }

            // Create order items
            foreach ($data['order_items'] as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'notes' => $item['notes'] ?? null,
                ]);

                if (!$orderItem) {
                    throw new \Exception('Failed to create order item');
                }
            }

            // Handle payment
            if ($data['payment_method'] === 'cash') {
                // For cash payment, create transaction immediately
                $transaction = Transaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => 'CSH-' . now()->format('Ymd') . '-' . $order->id,
                    'payment_method' => 'cash',
                    'amount' => $total,
                    'status' => 'success',
                    'paid_at' => now(),
                ]);

                if (!$transaction) {
                    throw new \Exception('Failed to create transaction');
                }

                // Show success notification
                \Filament\Notifications\Notification::make()
                    ->title('Order Created Successfully')
                    ->body("Order #{$order->order_number} has been created and paid.")
                    ->success()
                    ->send();

                // Redirect to orders list
                return redirect()->route('filament.cashier.resources.orders.index');
            } else {
                // For digital payments
                \Filament\Notifications\Notification::make()
                    ->title('Order Created')
                    ->body("Order #{$order->order_number} created. Payment required.")
                    ->warning()
                    ->send();

                return redirect()->route('filament.cashier.resources.orders.view', $order);
            }

        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error Creating Order')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Log the error for debugging
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'data' => $data ?? null,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Create Order')
                ->action('create'),
        ];
    }
}

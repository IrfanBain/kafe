<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DashboardDataSeeder extends Seeder
{
    public function run(): void
    {
        $tables = Table::all();
        $products = Product::all();

        if ($tables->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Tables or Products not found. Please run TableSeeder and ProductSeeder first.');
            return;
        }

        // Create sample orders for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);

            // Create 3-8 orders per day
            $ordersCount = rand(3, 8);

            for ($i = 0; $i < $ordersCount; $i++) {
                $table = $tables->random();
                $orderProducts = $products->random(rand(1, 4));
                $totalAmount = 0;

                $order = Order::create([
                    'order_number' => 'ORD-' . $date->format('Ymd') . '-' . str_pad(($day * 10) + $i + 1, 4, '0', STR_PAD_LEFT),
                    'table_id' => $table->id,
                    'customer_name' => 'Customer ' . ($i + 1),
                    'total_amount' => 0, // Will be updated later
                    'status' => $this->getRandomOrderStatus(),
                    'payment_status' => $this->getRandomPaymentStatus(),
                    'notes' => rand(0, 3) == 0 ? 'Special request: ' . fake()->sentence() : null,
                    'created_at' => $date->addHours(rand(8, 22))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->addHours(rand(8, 22))->addMinutes(rand(0, 59)),
                ]);

                // Create order items
                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $itemTotal = $product->price * $quantity;
                    $totalAmount += $itemTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'total' => $itemTotal,
                        'notes' => rand(0, 4) == 0 ? 'Extra spicy' : null,
                    ]);
                }

                // Update order total
                $order->update(['total_amount' => $totalAmount]);

                // Create transaction if payment is paid
                if ($order->payment_status === 'paid') {
                    Transaction::create([
                        'transaction_id' => 'TXN-' . $order->order_number . '-' . time() . rand(100, 999),
                        'order_id' => $order->id,
                        'payment_method' => $this->getRandomPaymentMethod(),
                        'amount' => $totalAmount,
                        'status' => 'success',
                        'midtrans_response' => json_encode([
                            'transaction_status' => 'settlement',
                            'payment_type' => $this->getRandomPaymentMethod(),
                            'gross_amount' => $totalAmount,
                        ]),
                        'snap_token' => 'snap_' . str()->random(32),
                        'paid_at' => $order->created_at->addMinutes(rand(5, 30)),
                        'created_at' => $order->created_at,
                        'updated_at' => $order->created_at->addMinutes(rand(5, 30)),
                    ]);
                }
            }
        }

        $this->command->info('Dashboard sample data created successfully!');
    }

    private function getRandomOrderStatus(): string
    {
        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'served', 'completed', 'cancelled'];
        $weights = [10, 15, 15, 10, 20, 25, 5]; // Higher chance for completed orders

        return $this->weightedRandom($statuses, $weights);
    }

    private function getRandomPaymentStatus(): string
    {
        $statuses = ['pending', 'paid', 'failed'];
        $weights = [20, 70, 10]; // Higher chance for paid status

        return $this->weightedRandom($statuses, $weights);
    }

    private function getRandomPaymentMethod(): string
    {
        $methods = ['credit_card', 'bank_transfer', 'e_wallet', 'qris'];
        return $methods[array_rand($methods)];
    }

    private function weightedRandom(array $values, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $randomValue = rand(1, $totalWeight);

        $currentWeight = 0;
        foreach ($values as $index => $value) {
            $currentWeight += $weights[$index];
            if ($randomValue <= $currentWeight) {
                return $value;
            }
        }

        return $values[0];
    }
}

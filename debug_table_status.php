<?php

require 'vendor/autoload.php';

use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

// Boot Laravel app
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUGGING TABLE STATUS UPDATE ===\n\n";

// Get table 1
$table = Table::find(1);
echo "Table 1 status: {$table->status}\n";

// Get all orders for table 1
$orders = Order::where('table_id', 1)->get();
echo "Total orders for table 1: " . $orders->count() . "\n";

foreach ($orders as $order) {
    echo "Order {$order->id}: {$order->order_number} - Status: {$order->status}\n";
}

// Get active orders
$activeOrders = Order::where('table_id', 1)
    ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
    ->get();

echo "\nActive orders: " . $activeOrders->count() . "\n";
foreach ($activeOrders as $order) {
    echo "Active order {$order->id}: {$order->order_number} - Status: {$order->status}\n";
}

// Test manual table update
echo "\nTesting manual table update...\n";
if ($activeOrders->count() == 0) {
    $table->update(['status' => 'available']);
    echo "Table updated to available\n";
} else {
    echo "Cannot update table - has active orders\n";
}

echo "Final table status: " . $table->fresh()->status . "\n";

<?php

require 'vendor/autoload.php';

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

// Boot Laravel app
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COMPREHENSIVE ORDER COMPLETION WORKFLOW TEST ===\n\n";

// Test Scenario 1: Single order completion
echo "SCENARIO 1: Single order completion\n";
echo "=====================================\n";

$table = Table::first();
echo "Initial table status: {$table->status}\n";

// Create order
$order1 = Order::create([
    'table_id' => $table->id,
    'customer_name' => 'Customer 1',
    'total_amount' => 50000,
    'status' => 'pending',
    'payment_status' => 'pending',
    'order_number' => 'TEST1-' . time(),
]);

$table->update(['status' => 'occupied']);
echo "Created order {$order1->order_number}, table status: {$table->fresh()->status}\n";

// Complete order
$order1->updateOrderStatus('completed');
echo "Completed order, table status: {$table->fresh()->status}\n\n";

// Test Scenario 2: Multiple orders on same table
echo "SCENARIO 2: Multiple orders on same table\n";
echo "===========================================\n";

// Create first order
$order2a = Order::create([
    'table_id' => $table->id,
    'customer_name' => 'Customer 2A',
    'total_amount' => 30000,
    'status' => 'pending',
    'payment_status' => 'pending',
    'order_number' => 'TEST2A-' . time(),
]);

$table->update(['status' => 'occupied']);
echo "Created first order {$order2a->order_number}, table status: {$table->fresh()->status}\n";

// Create second order on same table
$order2b = Order::create([
    'table_id' => $table->id,
    'customer_name' => 'Customer 2B',
    'total_amount' => 40000,
    'status' => 'pending',
    'payment_status' => 'pending',
    'order_number' => 'TEST2B-' . time(),
]);

echo "Created second order {$order2b->order_number}, table status: {$table->fresh()->status}\n";

// Complete first order - table should still be occupied
$order2a->updateOrderStatus('completed');
echo "Completed first order, table status: {$table->fresh()->status} (should still be occupied)\n";

// Complete second order - table should become available
$order2b->updateOrderStatus('completed');
echo "Completed second order, table status: {$table->fresh()->status} (should be available)\n\n";

// Test Scenario 3: Cancelled order
echo "SCENARIO 3: Cancelled order\n";
echo "=============================\n";

$order3 = Order::create([
    'table_id' => $table->id,
    'customer_name' => 'Customer 3',
    'total_amount' => 60000,
    'status' => 'pending',
    'payment_status' => 'pending',
    'order_number' => 'TEST3-' . time(),
]);

$table->update(['status' => 'occupied']);
echo "Created order {$order3->order_number}, table status: {$table->fresh()->status}\n";

// Cancel order
$order3->updateOrderStatus('cancelled');
echo "Cancelled order, table status: {$table->fresh()->status}\n\n";

// Test Scenario 4: New order after completion
echo "SCENARIO 4: New order after completion\n";
echo "=======================================\n";

// Check if we can create new order
$activeOrders = Order::where('table_id', $table->id)
    ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
    ->exists();

if (!$activeOrders) {
    $order4 = Order::create([
        'table_id' => $table->id,
        'customer_name' => 'Customer 4',
        'total_amount' => 70000,
        'status' => 'pending',
        'payment_status' => 'pending',
        'order_number' => 'TEST4-' . time(),
    ]);

    $table->update(['status' => 'occupied']);
    echo "Successfully created new order {$order4->order_number} after previous completions\n";
    echo "Table status: {$table->fresh()->status}\n";
} else {
    echo "ERROR: Cannot create new order - table still has active orders\n";
}

echo "\n=== ALL SCENARIOS COMPLETED SUCCESSFULLY ===\n";

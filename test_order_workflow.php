<?php

require 'vendor/autoload.php';

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Boot Laravel app
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING ORDER COMPLETION WORKFLOW ===\n\n";

// Test 1: Check initial table status
echo "1. Checking initial table status...\n";
$table = Table::first();
echo "Meja {$table->number}: {$table->status}\n\n";

// Test 2: Create a test order
echo "2. Creating test order...\n";
DB::beginTransaction();

try {
    $order = Order::create([
        'table_id' => $table->id,
        'customer_name' => 'Test Customer',
        'total_amount' => 50000,
        'status' => 'pending',
        'payment_status' => 'pending',
        'notes' => 'Test order for workflow',
        'order_number' => 'TEST-' . time(),
    ]);

    // Update table status to occupied
    $table->update(['status' => 'occupied']);

    echo "Order created: {$order->order_number}\n";
    echo "Table status after order created: {$table->fresh()->status}\n\n";

    // Test 3: Update order status through various stages
    echo "3. Testing order status progression...\n";

    $statuses = ['confirmed', 'preparing', 'ready'];
    foreach ($statuses as $status) {
        $order->updateOrderStatus($status);
        echo "Order status: {$status}, Table status: {$table->fresh()->status}\n";
    }
    echo "\n";

    // Test 4: Complete the order
    echo "4. Completing the order...\n";
    try {
        $order->updateOrderStatus('completed');
        $tableAfterCompletion = $table->fresh();
        echo "Order completed! Table status: {$tableAfterCompletion->status}\n\n";
    } catch (Exception $e) {
        echo "ERROR completing order: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n\n";
    }

    // Test 5: Check if new order can be created
    echo "5. Testing new order creation...\n";
    $activeOrder = Order::where('table_id', $table->id)
        ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
        ->first();

    if ($activeOrder) {
        echo "ERROR: Table still has active order: {$activeOrder->order_number}\n";
    } else {
        echo "SUCCESS: No active orders found. Table available for new orders.\n";

        // Try creating new order
        $newOrder = Order::create([
            'table_id' => $table->id,
            'customer_name' => 'New Customer',
            'total_amount' => 75000,
            'status' => 'pending',
            'payment_status' => 'pending',
            'notes' => 'Second test order',
            'order_number' => 'TEST2-' . time(),
        ]);

        echo "New order created successfully: {$newOrder->order_number}\n";
    }

    DB::commit();
    echo "\n=== WORKFLOW TEST COMPLETED SUCCESSFULLY ===\n";

} catch (Exception $e) {
    DB::rollback();
    echo "ERROR: " . $e->getMessage() . "\n";
}

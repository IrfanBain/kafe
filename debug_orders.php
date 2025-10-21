<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo "=== Checking Orders ===\n";

try {
    $orders = Order::all();
    echo "Total orders: " . $orders->count() . "\n\n";
    
    if ($orders->count() > 0) {
        echo "Order numbers:\n";
        foreach ($orders as $order) {
            echo "- " . $order->order_number . " (created: " . $order->created_at . ")\n";
        }
    } else {
        echo "No orders found in database\n";
    }
    
    echo "\n=== Checking Transactions ===\n";
    
    try {
        $transactions = \App\Models\Transaction::all();
        echo "Total transactions: " . $transactions->count() . "\n\n";
        
        if ($transactions->count() > 0) {
            echo "Transactions:\n";
            foreach ($transactions as $transaction) {
                echo "- Order: " . $transaction->order_id . "\n";
                echo "  Midtrans Order ID: " . ($transaction->midtrans_order_id ?? 'null') . "\n";
                echo "  Transaction ID: " . ($transaction->transaction_id ?? 'null') . "\n";
                echo "  Status: " . $transaction->status . "\n";
                echo "  Created: " . $transaction->created_at . "\n\n";
            }
        } else {
            echo "No transactions found\n";
        }
    } catch (\Exception $e) {
        echo "Error checking transactions: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Checking specific callback ===\n";
    $callbackOrderId = "ORD-20250929-1094-1759147523";
    $targetOrder = "ORD-20250929-1094";
    
    $order = Order::where('order_number', $targetOrder)->first();
    if ($order) {
        echo "Found order: $targetOrder\n";
        echo "Status: " . $order->status . "\n";
        echo "Total: " . $order->total_amount . "\n";
    } else {
        echo "Order $targetOrder NOT FOUND\n";
    }
    
    // Check by transaction_id (which might match the callback order_id)
    $transaction = \App\Models\Transaction::where('transaction_id', $callbackOrderId)->first();
    if ($transaction) {
        echo "Found transaction with transaction_id: $callbackOrderId\n";
        echo "Order ID: " . $transaction->order_id . "\n";
        $order = Order::find($transaction->order_id);
        if ($order) {
            echo "Related Order Number: " . $order->order_number . "\n";
        }
    } else {
        echo "No transaction found with transaction_id: $callbackOrderId\n";
        
        // Try to find transaction by similar pattern
        echo "\nSearching for similar transaction_id patterns...\n";
        $similarTransactions = \App\Models\Transaction::where('transaction_id', 'LIKE', 'ORD-20250929-%')->get();
        foreach ($similarTransactions as $trans) {
            echo "- " . $trans->transaction_id . " (Order: " . $trans->order_id . ")\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
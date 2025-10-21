<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CashierController extends Controller
{
    public function dashboard()
    {
        $todayOrders = Order::whereDate('created_at', today())
            ->with(['orderItems.product', 'transaction'])
            ->latest()
            ->paginate(10);

        $todayStats = [
            'total_orders' => Order::whereDate('created_at', today())->count(),
            'total_revenue' => Order::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'pending_orders' => Order::whereDate('created_at', today())
                ->where('status', 'pending')
                ->count(),
            'completed_orders' => Order::whereDate('created_at', today())
                ->where('status', 'completed')
                ->count(),
        ];

        return view('cashier.dashboard', compact('todayOrders', 'todayStats'));
    }

    public function createOrder()
    {
        $categories = Category::with('products')->get();

        return view('cashier.create-order', compact('categories'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_type' => 'required|in:dine_in,take_away',
            'table_id' => 'nullable|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,qris,bank_transfer',
        ]);

        // Calculate total
        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $totalAmount += $subtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
                'notes' => $item['notes'] ?? null,
            ];
        }

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'table_id' => $request->order_type === 'dine_in' ? $request->table_id : null,
            'customer_name' => $request->customer_name,
            'order_type' => $request->order_type,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'created_by_cashier' => true,
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
                'notes' => $item['notes'],
            ]);
        }

        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Prepare transaction data for Midtrans
        $transactionDetails = [
            'order_id' => $order->order_number,
            'gross_amount' => $totalAmount,
        ];

        $customerDetails = [
            'first_name' => $request->customer_name,
        ];

        $itemDetails = [];
        foreach ($order->orderItems as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
            ];
        }

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ];

        // Handle payment method
        if ($request->payment_method === 'cash') {
            // For cash payment, mark as paid immediately
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed'
            ]);

            Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => 'CSH-' . now()->format('Ymd') . '-' . $order->id,
                'payment_method' => 'cash',
                'amount' => $totalAmount,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            return redirect()->route('cashier.order.receipt', $order->order_number)
                ->with('success', 'Cash payment processed successfully!');
        } else {
            // For digital payments, create Midtrans Snap token
            try {
                $snapToken = Snap::getSnapToken($transactionData);

                // Create pending transaction
                Transaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => $order->order_number,
                    'payment_method' => $request->payment_method,
                    'amount' => $totalAmount,
                    'status' => 'pending',
                    'snap_token' => $snapToken,
                ]);

                return redirect()->route('cashier.payment', $order->order_number)
                    ->with('success', 'Order created! Please complete payment.');

            } catch (\Exception $e) {
                // If Midtrans fails, delete the order and show error
                $order->delete();
                return back()->withErrors(['payment' => 'Payment system error: ' . $e->getMessage()])->withInput();
            }
        }
    }

    public function showPayment($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['table', 'transaction', 'orderItems.product'])
            ->firstOrFail();

        // Check if order was created by cashier
        if (!$order->created_by_cashier) {
            abort(404);
        }

        return view('cashier.payment', compact('order'));
    }

    public function showReceipt($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['orderItems.product', 'transaction', 'table'])
            ->firstOrFail();

        return view('cashier.receipt', compact('order'));
    }

    public function orderHistory()
    {
        $orders = Order::where('created_by_cashier', true)
            ->with(['orderItems.product', 'transaction'])
            ->latest()
            ->paginate(20);

        return view('cashier.order-history', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled'
        ]);

        $order->updateOrderStatus($request->status);

        return back()->with('success', 'Order status updated successfully!');
    }
}

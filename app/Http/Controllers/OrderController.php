<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|min:10|regex:/^62/',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ],
        [
            // --- TAMBAHKAN PESAN ERROR KUSTOM ---
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'customer_phone.regex' => 'Format Nomor WhatsApp salah. Harus diawali 62.'
        ]
    );

        DB::beginTransaction();
        try {
            $table = Table::findOrFail($request->table_id);

            // Check if table already has an active order
            // Exclude 'completed' orders so table can accept new orders after completion
            $activeOrder = Order::where('table_id', $table->id)
                ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
                ->first();

            if ($activeOrder) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Meja ini sudah memiliki pesanan aktif.',
                        'redirect' => route('order.status', $activeOrder->order_number)
                    ], 422);
                }
                
                return redirect()->route('order.status', $activeOrder->order_number)
                    ->with('error', 'Meja ini sudah memiliki pesanan aktif.');
            }

            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $itemTotal,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            // Create order
            $order = Order::create([
                'table_id' => $table->id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
            }

            // Update table status
            $table->update(['status' => 'occupied']);

            DB::commit();

            // Check if this is an AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat',
                    'order_number' => $order->order_number,
                    'redirect' => route('payment.create', $order->order_number)
                ]);
            }

            // Redirect to payment for normal requests
            return redirect()->route('payment.create', $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Order Store Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membuat pesanan.'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat pesanan.']);
        }
    }

    public function status($orderNumber)
    {
        $order = Order::where('order_number', operator: $orderNumber)
            ->with(['table', 'orderItems.product', 'transaction'])
            ->firstOrFail();

        $snapToken = null;

        // Generate snap token if payment is still pending
        if ($order->payment_status === 'pending') {
            $snapToken = $this->getSnapToken($order);
        }

        return view('order.status', compact('order', 'snapToken'));
    }

    public function getSnapToken(Order $order)
    {
        try {
            // Check if transaction already exists with valid snap token
            $existingTransaction = $order->transaction;
            if ($existingTransaction && $existingTransaction->snap_token && $existingTransaction->status === 'pending') {
                Log::info('Using existing snap token', ['order_number' => $order->order_number]);
                return $existingTransaction->snap_token;
            }
            
            // Create unique order_id for Midtrans (to avoid duplicate order_id error)
            $orderIdForMidtrans = $order->order_number . '-' . time();

            // Prepare transaction data
            $transactionDetails = [
                'order_id' => $orderIdForMidtrans,
                'gross_amount' => (int) $order->total_amount,
            ];

            $customerDetails = [
                'first_name' => $order->customer_name ?: 'Customer',
                'last_name' => '',
                'email' => 'customer@example.com',
                'phone' => $order->customer_phone,
            ];

            $itemDetails = [];
            foreach ($order->orderItems as $item) {
                $itemDetails[] = [
                    'id' => (string) $item->product_id,
                    'price' => (int) $item->price,
                    'quantity' => (int) $item->quantity,
                    'name' => substr($item->product->name, 0, 50), // Limit name length
                ];
            }

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('order.status', $order->order_number),
                ],
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s O'),
                    'unit' => 'minutes',
                    'duration' => 60
                ]
            ];

            Log::info('Generating Midtrans snap token', [
                'order_number' => $order->order_number,
                'midtrans_order_id' => $orderIdForMidtrans,
                'amount' => $order->total_amount
            ]);

            $snapToken = null;

            // Try with custom service first
            try {
                $midtransService = new MidtransService();
                $snapToken = $midtransService->createSnapToken($transactionData);
                Log::info('Using custom Midtrans service', ['success' => !is_null($snapToken)]);
            } catch (\Exception $e) {
                Log::warning('Custom Midtrans service failed: ' . $e->getMessage());
                $snapToken = null;
            }

            // If custom service fails, try with original Midtrans library
            if (!$snapToken) {
                try {
                    Log::info('Falling back to original Midtrans library');
                    
                    // Configure Midtrans
                    Config::$serverKey = config('services.midtrans.server_key');
                    Config::$isProduction = config('services.midtrans.is_production');
                    Config::$isSanitized = config('services.midtrans.is_sanitized');
                    Config::$is3ds = config('services.midtrans.is_3ds');

                    // Don't set curlOptions for now to avoid the undefined array key issue

                    // Get snap token
                    $snapToken = Snap::getSnapToken($transactionData);
                    Log::info('Original Midtrans library succeeded');
                    
                } catch (\Exception $e) {
                    Log::error('Both Midtrans methods failed: ' . $e->getMessage());
                }
            }
            
            // Validate snap token
            if (!$snapToken) {
                Log::error('No snap token generated', [
                    'order_number' => $order->order_number,
                    'midtrans_order_id' => $orderIdForMidtrans
                ]);
                return null;
            }

            Log::info('Snap token generated successfully', [
                'order_number' => $order->order_number,
                'snap_token' => substr($snapToken, 0, 10) . '...',
                'snap_token_length' => strlen($snapToken)
            ]);

            // Save transaction
            Transaction::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'transaction_id' => $orderIdForMidtrans,
                    'payment_method' => 'midtrans',
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                    'snap_token' => $snapToken,
                    'midtrans_order_id' => $orderIdForMidtrans,
                ]
            );

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $order->total_amount,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}

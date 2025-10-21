<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function create($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['table', 'orderItems.product'])
            ->firstOrFail();

        // Check if already has transaction
        if ($order->transaction) {
            if ($order->transaction->status === 'success') {
                return redirect()->route('order.status', $order->order_number);
            }
        }

        // Generate Midtrans Snap Token
        $snapToken = app(OrderController::class)->getSnapToken($order);
        return view('payment.create', compact('order', 'snapToken'));
    }

    public function process(Request $request, $orderNumber)
    {
        // This method is no longer used as payment goes through Midtrans
        // Redirect to order status to show payment options
        return redirect()->route('order.status', $orderNumber);
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['table', 'transaction'])
            ->firstOrFail();

        return view('payment.success', compact('order'));
    }

    public function failed($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['table', 'transaction'])
            ->firstOrFail();

        return view('payment.failed', compact('order'));
    }

    public function callback(Request $request)
    {
        // Log raw request first
        Log::info('Midtrans callback received', [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'raw_body' => $request->getContent()
        ]);

        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Disable SSL verification for development
        if (config('app.env') === 'local' || config('app.debug') === true) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
        }

        // Always use fallback parsing to avoid Notification class issues
        try {
            Log::info('Parsing notification directly from request');
            
            $midtransOrderId = $request->input('order_id');
            $transactionStatus = $request->input('transaction_status');
            $fraudStatus = $request->input('fraud_status');
            $paymentType = $request->input('payment_type');

            if (!$midtransOrderId || !$transactionStatus) {
                Log::error('Missing required notification fields', [
                    'order_id' => $midtransOrderId,
                    'transaction_status' => $transactionStatus,
                    'all_input' => $request->all()
                ]);
                return response()->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
            }

            Log::info('Notification parsed successfully', [
                'midtrans_order_id' => $midtransOrderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType
            ]);

            // Process the notification
            return $this->processNotification($midtransOrderId, $transactionStatus, $fraudStatus, $paymentType, $request->all());

        } catch (\Exception $e) {
            Log::error('Midtrans notification class failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Fallback: Parse notification directly from request
            try {
                Log::info('Using fallback notification parsing');
                
                $midtransOrderId = $request->input('order_id');
                $transactionStatus = $request->input('transaction_status');
                $fraudStatus = $request->input('fraud_status');
                $paymentType = $request->input('payment_type');

                if (!$midtransOrderId || !$transactionStatus) {
                    Log::error('Missing required notification fields', [
                        'order_id' => $midtransOrderId,
                        'transaction_status' => $transactionStatus
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
                }

                Log::info('Fallback notification parsed', [
                    'midtrans_order_id' => $midtransOrderId,
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                    'payment_type' => $paymentType
                ]);

                // Process the notification using the same logic
                return $this->processNotification($midtransOrderId, $transactionStatus, $fraudStatus, $paymentType, $request->all());

            } catch (\Exception $fallbackError) {
                Log::error('Fallback notification parsing also failed', [
                    'error' => $fallbackError->getMessage(),
                    'trace' => $fallbackError->getTraceAsString(),
                    'request_data' => $request->all()
                ]);
                return response()->json(['status' => 'error', 'message' => 'Unable to process notification'], 500);
            }
        }
    }

    private function processNotification($midtransOrderId, $transactionStatus, $fraudStatus, $paymentType, $fullResponse)
    {
        try {
            // Extract order_number from midtrans order_id (remove timestamp suffix)
            $orderNumber = preg_replace('/-\d+$/', '', $midtransOrderId);

            Log::info('Extracting order number', [
                'midtrans_order_id' => $midtransOrderId,
                'extracted_order_number' => $orderNumber
            ]);

            // Find the order by order_number
            $order = Order::where('order_number', $orderNumber)->first();
            if (!$order) {
                // Try to find order by transaction table if direct lookup fails
                $transaction = Transaction::where('transaction_id', $midtransOrderId)->first();
                
                if ($transaction) {
                    $order = $transaction->order;
                    Log::info('Order found via transaction lookup', [
                        'transaction_id' => $midtransOrderId,
                        'order_id' => $transaction->order_id,
                        'order_number' => $order->order_number
                    ]);
                }
            }

            if (!$order) {
                Log::warning('Order not found - might be old/expired callback', [
                    'midtrans_order_id' => $midtransOrderId,
                    'extracted_order_number' => $orderNumber,
                    'available_orders' => Order::pluck('order_number')->toArray(),
                    'available_transaction_ids' => Transaction::pluck('transaction_id')->toArray()
                ]);
                
                // Return success to avoid retry from Midtrans, but log the issue
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Order not found - likely expired/old callback'
                ], 200);
            }

            $transaction = $order->transaction;
            if (!$transaction) {
                // Create transaction if not exists
                $transaction = Transaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => $midtransOrderId,
                    'payment_method' => 'midtrans',
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                    'midtrans_order_id' => $midtransOrderId,
                ]);
                Log::info('Transaction created in callback', [
                    'order_number' => $orderNumber,
                    'midtrans_order_id' => $midtransOrderId
                ]);
            }

            // Update transaction based on status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->update([
                        'status' => 'challenge',
                        'midtrans_response' => $fullResponse
                    ]);
                    Log::info('Transaction status: challenge', ['order_number' => $orderNumber]);
                } else if ($fraudStatus == 'accept') {
                    $transaction->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'midtrans_response' => $fullResponse
                    ]);
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed'
                    ]);
                    Log::info('Transaction successful via capture', ['order_number' => $orderNumber]);
                    // Send WhatsApp notification if number exists  
                    $this->sendWhatsappNotification($order);
                }
            } else if ($transactionStatus == 'settlement') {
                $transaction->update([
                    'status' => 'success',
                    'paid_at' => now(),
                    'midtrans_response' => $fullResponse
                ]);
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);
                Log::info('Transaction successful via settlement', ['order_number' => $orderNumber]);
                $this->sendWhatsappNotification($order);

            } else if ($transactionStatus == 'pending') {
                $transaction->update([
                    'status' => 'pending',
                    'midtrans_response' => $fullResponse
                ]);
                Log::info('Transaction pending', ['order_number' => $orderNumber]);
            } else if ($transactionStatus == 'deny') {
                $transaction->update([
                    'status' => 'denied',
                    'midtrans_response' => $fullResponse
                ]);
                $order->update(['payment_status' => 'failed']);
                Log::info('Transaction denied', ['order_number' => $orderNumber]);
            } else if ($transactionStatus == 'expire') {
                $transaction->update([
                    'status' => 'expired',
                    'midtrans_response' => $fullResponse
                ]);
                $order->update(['payment_status' => 'failed']);
                Log::info('Transaction expired', ['order_number' => $orderNumber]);
            } else if ($transactionStatus == 'cancel') {
                $transaction->update([
                    'status' => 'cancelled',
                    'midtrans_response' => $fullResponse
                ]);
                $order->update(['payment_status' => 'cancelled']);
                Log::info('Transaction cancelled', ['order_number' => $orderNumber]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Process notification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'midtrans_order_id' => $midtransOrderId,
                'transaction_status' => $transactionStatus
            ]);
            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 500);
        }
    }

    // --- FUNGSI BARU ---
    /**
     * Mengirim notifikasi WhatsApp menggunakan Fonnte
     * setelah pembayaran berhasil.
     *
     * @param Order $order
     */
    private function sendWhatsappNotification(Order $order)
    {
        // Ambil nomor WA dari kolom 'customer_phone' di tabel Order
        // Pastikan kolom ini sudah ada dan terisi di Tahap 2 (Checkout)
        $targetNumber = $order->customer_phone;

        $tokenDebug = env('FONNTE_TOKEN');
    Log::debug('Mencoba mengirim WA untuk order: ' . $order->order_number . ' menggunakan token: ' . substr($tokenDebug, 0, 5) . '...' . substr($tokenDebug, -5));

        if (empty($targetNumber)) {
            Log::warning('Gagal kirim WA: Nomor customer_phone kosong.', ['order_number' => $order->order_number]);
            return;
        }
        
        // Buat pesan notifikasi
        // (Anda bisa sesuaikan pesannya)
        // Siapkan rincian item
$itemDetails = "";
foreach ($order->orderItems as $item) {
    // Pastikan relasi 'product' ada dan terload
    if ($item->product) { 
        $itemDetails .= "- " . $item->quantity . "x " . $item->product->name . "\n";
    } else {
        $itemDetails .= "- " . $item->quantity . "x Item (ID: " . $item->product_id . ")\n"; // Fallback jika produk tidak terload
    }
}

// Tambahkan nomor meja jika ada (pastikan relasi 'table' ada)
$tableInfo = "";
if ($order->table) {
    $tableInfo = "Meja: *" . $order->table->number . "*\n"; // Asumsi ada kolom 'number' di tabel 'tables'
}


$message = "🎉 *Pembayaran Berhasil di Kafe Digital!* 🎉\n\n" .
           "Halo *{$order->customer_name}*,\n\n" .
           "Terima kasih! Pembayaran pesanan Anda *#{$order->order_number}* telah kami konfirmasi.\n\n" .
           "🧾 *Rincian Pesanan:*\n" .
           $itemDetails . // Masukkan detail item
           $tableInfo .   // Masukkan info meja (jika ada)
           "\n" .
           "💰 *Total Pembayaran:* Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n" .
           "Mohon tunggu sebentar, pesanan Anda sedang kami siapkan. 😊\n\n" .
           "Terima kasih,\n" .
           "*Tim Kafe Digital*";

        try {
            $response = Http::withHeaders([
                'Authorization' => $tokenDebug // Ambil token dari .env
            ])->post('https://api.fonnte.com/send', [
                'target' => $targetNumber,
                'message' => $message,
                'countryCode' => '62', // Opsional
            ]);
            
            // Catat respons dari Fonnte di log
            Log::info('Fonnte Response: ' . $response->body(), ['order_number' => $order->order_number]);

        } catch (\Exception $e) {
            // Catat jika terjadi error saat koneksi ke Fonnte
            Log::error('Fonnte Send Error: ' . $e->getMessage(), ['order_number' => $order->order_number]);
        }
    }
    // --- SELESAI FUNGSI BARU ---

}
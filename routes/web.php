<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QrCodeController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Categories
Route::get('/categories', [HomeController::class, 'categories'])->name('categories.index');
Route::get('/category/{category}', [HomeController::class, 'showCategory'])->name('category.show');

// Product detail
Route::get('/product/{product}', [HomeController::class, 'showProduct'])->name('product.show');

// QR Code routes
Route::get('/table/{qrCode}', [MenuController::class, 'show'])->name('table.menu');
Route::get('/qr/{qrCode}', [QrCodeController::class, 'show'])->name('qr.show');

// Orders
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/order/{orderNumber}/status', [OrderController::class, 'status'])->name('order.status');

// Payment
Route::get('/payment/{orderNumber}', [PaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/{orderNumber}/process', [PaymentController::class, 'process'])->name('payment.process');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback')->withoutMiddleware(['web']);
Route::get('/payment/{orderNumber}/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/{orderNumber}/failed', [PaymentController::class, 'failed'])->name('payment.failed');

// Cashier routes (separate from Filament admin panel)
Route::prefix('cashier-pos')->name('cashier.')->group(function () {
    Route::get('/', [CashierController::class, 'dashboard'])->name('dashboard');
    Route::get('/create-order', [CashierController::class, 'createOrder'])->name('create-order');
    Route::post('/orders', [CashierController::class, 'storeOrder'])->name('orders.store');
    Route::get('/payment/{orderNumber}', [CashierController::class, 'showPayment'])->name('payment');
    Route::get('/receipt/{orderNumber}', [CashierController::class, 'showReceipt'])->name('order.receipt');
    Route::get('/order-history', [CashierController::class, 'orderHistory'])->name('order-history');
    Route::patch('/orders/{order}/status', [CashierController::class, 'updateOrderStatus'])->name('orders.update-status');
});

// // Test routes for Midtrans (remove in production)
// Route::get('/test/midtrans-config', [App\Http\Controllers\MidtransTestController::class, 'testConfig']);
// Route::get('/test/midtrans-token', [App\Http\Controllers\MidtransTestController::class, 'testSnapToken']);
// Route::get('/test/callback/{orderNumber}', function($orderNumber) {
//     // Simulate Midtrans callback for testing
//     $testData = [
//         'order_id' => $orderNumber,
//         'transaction_status' => 'settlement',
//         'payment_type' => 'bank_transfer',
//         'transaction_time' => date('Y-m-d H:i:s'),
//         'gross_amount' => '100000.00'
//     ];

//     $response = Http::post(route('payment.callback'), $testData);
//     return response()->json([
//         'test_data' => $testData,
//         'callback_response' => $response->json(),
//         'status' => $response->status()
//     ]);
// });

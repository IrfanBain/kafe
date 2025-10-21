<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;

class MidtransTestController extends Controller
{
    public function testConfig()
    {
        return response()->json([
            'client_key' => config('services.midtrans.client_key'),
            'server_key' => substr(config('services.midtrans.server_key'), 0, 20) . '...',
            'is_production' => config('services.midtrans.is_production'),
            'is_sanitized' => config('services.midtrans.is_sanitized'),
            'is_3ds' => config('services.midtrans.is_3ds'),
        ]);
    }

    public function testSnapToken(Request $request)
    {
        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = config('services.midtrans.is_sanitized');
            Config::$is3ds = config('services.midtrans.is_3ds');

            $transactionData = [
                'transaction_details' => [
                    'order_id' => 'TEST-' . time(),
                    'gross_amount' => 10000,
                ],
                'customer_details' => [
                    'first_name' => 'Test',
                    'last_name' => 'Customer',
                    'email' => 'test@example.com',
                    'phone' => '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => 'test-item',
                        'price' => 10000,
                        'quantity' => 1,
                        'name' => 'Test Item',
                    ]
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($transactionData);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'message' => 'Snap token generated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}

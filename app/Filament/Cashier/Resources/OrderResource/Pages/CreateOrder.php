<?php

namespace App\Filament\Cashier\Resources\OrderResource\Pages;

use App\Filament\Cashier\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['order_number'] = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        $data['created_by_cashier'] = true;

        // Calculate total from order items
        $total = 0;
        if (isset($data['orderItems'])) {
            foreach ($data['orderItems'] as $item) {
                $total += $item['subtotal'];
            }
        }
        $data['total_amount'] = $total;

        // Set initial status based on payment method
        if ($data['payment_method'] === 'cash') {
            $data['status'] = 'confirmed';
            $data['payment_status'] = 'paid';
        } else {
            $data['status'] = 'pending';
            $data['payment_status'] = 'pending';
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $order = static::getModel()::create($data);

        // Handle payment processing
        if ($data['payment_method'] === 'cash') {
            // For cash payment, create transaction immediately
            Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => 'CSH-' . now()->format('Ymd') . '-' . $order->id,
                'payment_method' => 'cash',
                'amount' => $data['total_amount'],
                'status' => 'success',
                'paid_at' => now(),
            ]);
        } else {
            // For digital payments, create Midtrans Snap token
            try {
                // Configure Midtrans
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = config('services.midtrans.is_sanitized');
                Config::$is3ds = config('services.midtrans.is_3ds');

                // Prepare transaction data
                $transactionDetails = [
                    'order_id' => $order->order_number,
                    'gross_amount' => $data['total_amount'],
                ];

                $customerDetails = [
                    'first_name' => $data['customer_name'],
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

                $snapToken = Snap::getSnapToken($transactionData);

                // Create pending transaction
                Transaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => $order->order_number,
                    'payment_method' => $data['payment_method'],
                    'amount' => $data['total_amount'],
                    'status' => 'pending',
                    'snap_token' => $snapToken,
                ]);

            } catch (\Exception $e) {
                // If Midtrans fails, still create order but mark payment as failed
                Transaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => 'FAIL-' . now()->format('Ymd') . '-' . $order->id,
                    'payment_method' => $data['payment_method'],
                    'amount' => $data['total_amount'],
                    'status' => 'failed',
                ]);
            }
        }

        return $order;
    }

    protected function getRedirectUrl(): string
    {
        $order = $this->record;

        if ($order->payment_status === 'paid') {
            return route('cashier.order.receipt', $order->order_number);
        } else {
            return route('cashier.payment', $order->order_number);
        }
    }
}

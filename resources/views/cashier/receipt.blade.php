<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt - Order {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { font-size: 12px; }
        }
        .print-only { display: none; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg no-print">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('cashier.dashboard') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Receipt - {{ $order->order_number }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-print mr-2"></i>Print Receipt
                    </button>
                    <a href="{{ route('cashier.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Receipt -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kafe Management System</h1>
                <p class="text-gray-600">Jl. Contoh No. 123, Jakarta</p>
                <p class="text-gray-600">Tel: (021) 123-4567</p>
                <hr class="my-4">
            </div>

            <!-- Order Details -->
            <div class="mb-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Order Number:</p>
                        <p class="font-semibold">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date & Time:</p>
                        <p class="font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Customer:</p>
                        <p class="font-semibold">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Order Type:</p>
                        <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</p>
                        @if($order->table)
                        <p class="text-sm text-gray-600">Table {{ $order->table->number }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="mb-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-300">
                            <th class="text-left py-2">Item</th>
                            <th class="text-center py-2">Qty</th>
                            <th class="text-right py-2">Price</th>
                            <th class="text-right py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-2">
                                <div>
                                    <p class="font-medium">{{ $item->product->name }}</p>
                                    @if($item->notes)
                                    <p class="text-sm text-gray-600">Note: {{ $item->notes }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center py-2">{{ $item->quantity }}</td>
                            <td class="text-right py-2">Rp {{ number_format($item->price) }}</td>
                            <td class="text-right py-2">Rp {{ number_format($item->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-300">
                            <td colspan="3" class="py-2 font-semibold text-right">Subtotal:</td>
                            <td class="py-2 font-semibold text-right">Rp {{ number_format($order->total_amount) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-2 font-bold text-right text-lg">TOTAL:</td>
                            <td class="py-2 font-bold text-right text-lg">Rp {{ number_format($order->total_amount) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Info -->
            @if($order->transaction)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">Payment Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Payment Method:</p>
                        <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->transaction->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Transaction ID:</p>
                        <p class="font-semibold">{{ $order->transaction->transaction_id }}</p>
                    </div>
                </div>
            </div>
            @endif            <!-- Status -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Order Status:</p>
                        <span class="px-3 py-1 text-sm rounded-full
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                            @elseif($order->status === 'ready') bg-purple-100 text-purple-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Payment Status:</p>
                        <span class="px-3 py-1 text-sm rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-600 border-t pt-4">
                <p>Thank you for your order!</p>
                <p>Please keep this receipt for your records.</p>
                <p class="mt-2">For support, contact us at support@kafemanagement.com</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 text-center space-x-4 no-print">
            <a href="{{ route('cashier.create-order') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>New Order
            </a>
            <a href="{{ route('cashier.dashboard') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment - Order {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Midtrans Snap -->
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('cashier.dashboard') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Payment - {{ $order->order_number }}</h1>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('cashier.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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

            <!-- Items -->
            <div class="mb-6">
                <h3 class="font-medium text-gray-900 mb-3">Items</h3>
                <div class="space-y-2">
                    @foreach($order->orderItems as $item)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-600">{{ $item->quantity }} x Rp {{ number_format($item->price) }}</p>
                            @if($item->notes)
                            <p class="text-xs text-gray-500">Note: {{ $item->notes }}</p>
                            @endif
                        </div>
                        <p class="font-semibold">Rp {{ number_format($item->subtotal) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Total -->
            <div class="border-t pt-4">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>Total Amount:</span>
                    <span class="text-blue-600">Rp {{ number_format($order->total_amount) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Complete Payment</h2>

            @if($order->transaction && $order->transaction->snap_token)
            <div class="text-center">
                <p class="text-gray-600 mb-6">Click the button below to complete payment using {{ ucfirst(str_replace('_', ' ', $order->transaction->payment_method)) }}</p>

                <button id="pay-button" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 text-lg font-semibold">
                    <i class="fas fa-credit-card mr-2"></i>Pay Now - Rp {{ number_format($order->total_amount) }}
                </button>

                <p class="text-sm text-gray-500 mt-4">
                    Secure payment powered by Midtrans
                </p>
            </div>

            <script type="text/javascript">
                var payButton = document.getElementById('pay-button');
                payButton.addEventListener('click', function () {
                    window.snap.pay('{{ $order->transaction->snap_token }}', {
                        onSuccess: function(result) {
                            console.log(result);
                            // Redirect to success page
                            window.location.href = '{{ route("payment.success", $order->order_number) }}';
                        },
                        onPending: function(result) {
                            console.log(result);
                            alert('Payment is pending. Please complete your payment.');
                        },
                        onError: function(result) {
                            console.log(result);
                            alert('Payment failed. Please try again.');
                        },
                        onClose: function() {
                            alert('You closed the popup without finishing the payment');
                        }
                    });
                });
            </script>
            @else
            <div class="text-center">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <p>Payment token not found. Please contact support.</p>
                </div>
                <a href="{{ route('cashier.dashboard') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                    Back to Dashboard
                </a>
            </div>
            @endif
        </div>

        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Order Status:</p>
                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment Status:</p>
                    <span class="px-3 py-1 text-sm rounded-full bg-orange-100 text-orange-800">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 text-center space-x-4">
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

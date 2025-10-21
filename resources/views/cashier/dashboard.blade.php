<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cashier Dashboard - Kafe Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Cashier Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cashier.create-order') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>New Order
                    </a>
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['total_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Revenue</p>
                        <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($todayStats['total_revenue']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['pending_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['completed_orders'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Today's Orders</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($todayOrders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full {{ $order->order_type === 'take_away' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($order->total_amount) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                    @elseif($order->status === 'ready') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                @if($order->created_by_cashier)
                                <a href="{{ route('cashier.order.receipt', $order->order_number) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-receipt"></i> Receipt
                                </a>
                                @endif
                                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                <form method="POST" action="{{ route('cashier.orders.update-status', $order) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs border rounded px-2 py-1">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No orders today</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($todayOrders->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $todayOrders->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>

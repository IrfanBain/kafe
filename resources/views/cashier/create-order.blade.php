<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Order - Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50" x-data="orderForm()">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('cashier.dashboard') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Create New Order</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cashier.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Selection -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Select Products</h2>
                    </div>
                    <div class="p-6">
                        @foreach($categories as $category)
                        <div class="mb-8">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">{{ $category->name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($category->products as $product)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded mb-3">
                                    @endif
                                    <h4 class="font-medium text-gray-900 mb-2">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3">{{ $product->description }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-blue-600">Rp {{ number_format($product->price) }}</span>
                                        <button @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                                                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                            Add
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow sticky top-6">
                    <div class="px-6 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                    </div>

                    <form method="POST" action="{{ route('cashier.orders.store') }}" class="p-6">
                        @csrf

                        <!-- Customer Info -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                            <input type="text" name="customer_name" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('customer_name') }}">
                            @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Type *</label>
                            <select name="order_type" x-model="orderType" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="take_away">Take Away</option>
                                <option value="dine_in">Dine In</option>
                            </select>
                        </div>

                        <!-- Table Selection (for dine in) -->
                        <div x-show="orderType === 'dine_in'" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Table</label>
                            <select name="table_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Table</option>
                                @for($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}">Table {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Cart Items -->
                        <div class="mb-4">
                            <h3 class="text-md font-medium text-gray-700 mb-2">Items</h3>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                <template x-for="(item, index) in cart" :key="index">
                                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm" x-text="item.name"></p>
                                            <p class="text-xs text-gray-600">Rp <span x-text="formatNumber(item.price)"></span></p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" @click="updateQuantity(index, -1)" class="w-6 h-6 bg-red-100 text-red-600 rounded-full text-xs hover:bg-red-200">-</button>
                                            <span class="text-sm font-medium" x-text="item.quantity"></span>
                                            <button type="button" @click="updateQuantity(index, 1)" class="w-6 h-6 bg-green-100 text-green-600 rounded-full text-xs hover:bg-green-200">+</button>
                                            <button type="button" @click="removeFromCart(index)" class="w-6 h-6 bg-red-100 text-red-600 rounded-full text-xs hover:bg-red-200">×</button>
                                        </div>
                                    </div>
                                    <!-- Hidden inputs for cart items -->
                                    <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.id">
                                    <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
                                </template>

                                <div x-show="cart.length === 0" class="text-center text-gray-500 py-4">
                                    No items in cart
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span>Rp <span x-text="formatNumber(getTotal())"></span></span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                            <select name="payment_method" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                            @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" :disabled="cart.length === 0" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed">
                            Create Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function orderForm() {
            return {
                cart: [],
                orderType: 'take_away',

                addToCart(id, name, price) {
                    const existingItem = this.cart.find(item => item.id === id);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.push({
                            id: id,
                            name: name,
                            price: price,
                            quantity: 1
                        });
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                updateQuantity(index, change) {
                    this.cart[index].quantity += change;
                    if (this.cart[index].quantity <= 0) {
                        this.removeFromCart(index);
                    }
                },

                getTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            }
        }
    </script>
</body>
</html>

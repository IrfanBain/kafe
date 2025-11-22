@extends('layouts.app')

@section('title', $storeSettings['store_name'] . ' - Menu - Meja ' . $table->number)

@section('content')
<div x-data="menuApp()" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Table Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $storeSettings['store_name'] }}</h1>
                <p class="text-gray-600">Meja {{ $table->number }} - Kapasitas {{ $table->capacity }} orang</p>
                @if($storeSettings['store_description'])
                    <p class="text-sm text-gray-500 mt-1">{{ $storeSettings['store_description'] }}</p>
                @endif
            </div>
            <div class="text-right">
                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ ucfirst($table->status) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Info Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelanggan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama *</label>
                <input
                    type="text"
                    id="customer_name"
                    x-model="customerName"
                    @blur="validateCustomerName()"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    :class="{ 'border-red-500': errors.customerName, 'border-gray-300': !errors.customerName }"
                    placeholder="Masukkan nama Anda"
                    required
                >
                <p x-show="errors.customerName" class="text-red-500 text-xs mt-1" x-text="errors.customerName"></p>
            </div>
            <div>
                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Wa *</label>
                <input
                    type="text"
                    id="customer_phone"
                    x-model="customerPhone"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    :class="{ 'border-red-500': errors.customerPhone, 'border-gray-300': !errors.customerPhone }"
                    placeholder="nomor wa diawali 62"
                    required
                >
                <p x-show="errors.customerPhone" class="text-red-500 text-xs mt-1" x-text="errors.customerPhone"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Meja</label>
                <input
                    type="text"
                    value="{{ $table->number }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                    readonly
                >
            </div>
        </div>
    </div>

    <!-- Menu Categories -->
    <div class="space-y-8 pb-32" x-bind:class="{ 'pb-32': cartItems.length > 0, 'pb-8': cartItems.length === 0 }">
        @if($categories->count() > 0)
            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $category->name }}</h2>
                            @if($category->description)
                                <p class="text-gray-600 mt-1">{{ $category->description }}</p>
                            @endif
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($category->products as $product)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300" 
                                         data-product-id="{{ $product->id }}" 
                                         data-product-name="{{ $product->name }}"
                                         data-product-price="{{ $product->price }}">
                                        <div class="flex space-x-4">
                                            <div class="relative">
                                                @if($product->image)
                                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg">
                                                @else
                                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">No Image</span>
                                                    </div>
                                                @endif
                                                <a href="{{ route('product.show', $product) }}" 
                                                   class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition duration-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white opacity-0 hover:opacity-100 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            </div>

                                            <div class="flex-1">
                                                <div class="flex items-start justify-between">
                                                    <h3 class="font-semibold text-gray-900 product-name">
                                                        <a href="{{ route('product.show', $product) }}" class="hover:text-blue-600 transition duration-200">
                                                            {{ $product->name }}
                                                        </a>
                                                    </h3>
                                                    <a href="{{ route('product.show', $product) }}" 
                                                       class="text-gray-400 hover:text-blue-600 transition duration-200 ml-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                                @if($product->description)
                                                    <p class="text-gray-600 text-sm mt-1">{{ Str::limit($product->description, 80) }}</p>
                                                @endif
                                                <div class="flex items-center justify-between mt-2">
                                                    <p class="text-lg font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                    @if($product->stock !== null)
                                                        <span class="text-xs px-2 py-1 rounded-full {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                            @if($product->stock > 10)
                                                                Tersedia
                                                            @elseif($product->stock > 0)
                                                                Stok: {{ $product->stock }}
                                                            @else
                                                                Habis
                                                            @endif
                                                        </span>
                                                    @else
                                                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">Tersedia</span>
                                                    @endif
                                                </div>

                                                <div class="flex items-center space-x-2 mt-3">
                                                    <button
                                                        @click="decreaseQuantity({{ $product->id }})"
                                                        class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-300 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        :disabled="getQuantity({{ $product->id }}) <= 0"
                                                    >
                                                        -
                                                    </button>
                                                    <span class="w-8 text-center font-medium" x-text="getQuantity({{ $product->id }})">0</span>
                                                    <button
                                                        @click="increaseQuantity({{ $product->id }}, {{ $product->price }}, '{{ addslashes($product->name) }}')"
                                                        class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-blue-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400"
                                                        @if($product->stock !== null && $product->stock <= 0) disabled @endif
                                                    >
                                                        +
                                                    </button>
                                                    @if($product->stock !== null && $product->stock <= 0)
                                                        <span class="text-xs text-red-500 ml-2">Habis</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Menu Belum Tersedia</h3>
                <p class="text-gray-600 mb-4">Saat ini belum ada menu yang tersedia untuk meja ini.</p>
                <p class="text-sm text-gray-500">Silakan hubungi staff kami untuk informasi lebih lanjut.</p>
            </div>
        @endif
    </div>

    <!-- Cart Summary -->
    <div x-show="cartItems.length > 0" class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg p-4 z-50 transition-all duration-300">
        <div class="max-w-4xl mx-auto">
            <!-- Mobile Cart Summary -->
            <div class="block sm:hidden">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm text-gray-600"><span x-text="cartItems.length"></span> items dipilih</p>
                        <p class="text-lg font-bold text-gray-900">Total: Rp <span x-text="formatPrice(totalAmount)"></span></p>
                    </div>
                    <button
                        @click="showOrderModal = true"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 text-sm"
                        :disabled="cartItems.length === 0 || !customerName.trim()"
                        :class="{ 'opacity-50 cursor-not-allowed': cartItems.length === 0 || !customerName.trim() }"
                    >
                        Pesan
                    </button>
                </div>
                <!-- Quick cart preview for mobile -->
                <div class="flex items-center space-x-2 overflow-x-auto pb-1">
                    <template x-for="item in cartItems.slice(0, 3)" :key="item.product_id">
                        <div class="flex-shrink-0 bg-gray-100 rounded-md px-2 py-1 text-xs">
                            <span x-text="item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name"></span>
                            <span class="text-blue-600 font-medium">×<span x-text="item.quantity"></span></span>
                        </div>
                    </template>
                    <div x-show="cartItems.length > 3" class="flex-shrink-0 text-xs text-gray-500">
                        +<span x-text="cartItems.length - 3"></span> lainnya
                    </div>
                </div>
            </div>
            
            <!-- Desktop Cart Summary -->
            <div class="hidden sm:flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600"><span x-text="cartItems.length"></span> items dipilih</p>
                    <p class="text-lg font-bold text-gray-900">Total: Rp <span x-text="formatPrice(totalAmount)"></span></p>
                </div>
                <button
                    @click="showOrderModal = true"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200"
                    :disabled="cartItems.length === 0 || !customerName.trim()"
                    :class="{ 'opacity-50 cursor-not-allowed': cartItems.length === 0 || !customerName.trim() }"
                >
                    Pesan Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Order Confirmation Modal -->
    <div x-show="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-cloak>
        <div class="bg-white rounded-lg max-w-md w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Pesanan</h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Nama: <span class="font-medium" x-text="customerName"></span></p>
                    <p class="text-sm text-gray-600">Meja: <span class="font-medium">{{ $table->number }}</span></p>
                </div>

                <div class="space-y-3 mb-4">
                    <template x-for="item in cartItems" :key="item.product_id">
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span x-text="item.name"></span>
                                <span class="text-gray-500">x<span x-text="item.quantity"></span></span>
                            </div>
                            <span class="font-medium">Rp <span x-text="formatPrice(item.total)"></span></span>
                        </div>
                    </template>
                </div>

                <div class="border-t pt-3 mb-4">
                    <div class="flex justify-between items-center font-semibold">
                        <span>Total</span>
                        <span>Rp <span x-text="formatPrice(totalAmount)"></span></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea
                        id="notes"
                        x-model="notes"
                        rows="3"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tambahkan catatan untuk pesanan Anda..."
                    ></textarea>
                </div>

                <div class="flex space-x-3">
                    <button
                        @click="showOrderModal = false"
                        class="flex-1 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-200"
                        :disabled="isSubmitting"
                    >
                        Batal
                    </button>
                    <button
                        @click="submitOrder()"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center"
                        :disabled="isSubmitting || !isValidOrder"
                        :class="{ 'opacity-50 cursor-not-allowed': isSubmitting || !isValidOrder }"
                    >
                        <span x-show="!isSubmitting">Konfirmasi Pesanan</span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function menuApp() {
    return {
        cartItems: [],
        customerName: '',
        customerPhone: '',
        notes: '',
        showOrderModal: false,
        isSubmitting: false,
        errors: {},

        get totalAmount() {
            return this.cartItems.reduce((sum, item) => sum + item.total, 0);
        },

        get isValidOrder() {
            // Validasi no WA simpel: harus mulai 62 dan minimal 10 digit total
            const phoneRegex = /^62\d{8,}$/; 
            return this.customerName.trim().length >= 2 && 
                   phoneRegex.test(this.customerPhone) && 
                   this.cartItems.length > 0;
        },

        getQuantity(productId) {
            const item = this.cartItems.find(item => item.product_id === productId);
            return item ? item.quantity : 0;
        },

        validateCustomerName() {
            if (this.customerName.trim().length < 2) {
                this.errors.customerName = 'Nama minimal 2 karakter';
                return false;
            }
            delete this.errors.customerName;
            return true;
        },

        // --- TAMBAHKAN FUNGSI VALIDASI INI ---
        validateCustomerPhone() {
            // Regex: harus mulai 62, diikuti 8-12 digit angka
            const phoneRegex = /^62\d{8,12}$/; 
            if (!this.customerPhone) {
                this.errors.customerPhone = 'Nomor WhatsApp wajib diisi';
                return false;
            }
            if (!phoneRegex.test(this.customerPhone)) {
                this.errors.customerPhone = 'Format salah. Harus diawali 62 (cth: 62812...)';
                return false;
            }
            delete this.errors.customerPhone;
            return true;
        },

        increaseQuantity(productId, price, productName) {
            const existingItem = this.cartItems.find(item => item.product_id === productId);

            if (existingItem) {
                existingItem.quantity++;
                existingItem.total = existingItem.quantity * price;
            } else {
                this.cartItems.push({
                    product_id: productId,
                    name: productName,
                    price: price,
                    quantity: 1,
                    total: price
                });
            }
        },

        decreaseQuantity(productId) {
            const existingItem = this.cartItems.find(item => item.product_id === productId);

            if (existingItem) {
                existingItem.quantity--;
                existingItem.total = existingItem.quantity * existingItem.price;

                if (existingItem.quantity <= 0) {
                    this.cartItems = this.cartItems.filter(item => item.product_id !== productId);
                }
            }
        },

        formatPrice(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        },

        showAlert(message, type = 'error') {
            // Create a simple alert system
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                type === 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white'
            }`;
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        },

        submitOrder() {
            // Reset previous errors
            this.errors = {};

            // Validate form
            if (!this.validateCustomerName()) {
                const isNameValid = this.validateCustomerName();
                const isPhoneValid = this.validateCustomerPhone(); // Panggil validasi baru

                if (!isNameValid || !isPhoneValid) { // Cek keduanya
                    this.showAlert('Mohon periksa kembali nama dan nomor WhatsApp Anda.');
                    return;
            }
            }

            if (this.cartItems.length === 0) {
                this.showAlert('Mohon pilih minimal satu item');
                return;
            }

            this.isSubmitting = true;

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('table_id', {{ $table->id }});
            formData.append('customer_name', this.customerName.trim());
            formData.append('customer_phone', this.customerPhone.trim());
            formData.append('notes', this.notes.trim());

            this.cartItems.forEach((item, index) => {
                formData.append(`items[${index}][product_id]`, item.product_id);
                formData.append(`items[${index}][quantity]`, item.quantity);
            });

            fetch('{{ route("orders.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    if (response.headers.get('content-type')?.includes('application/json')) {
                        return response.json();
                    } else {
                        // If redirect response, handle it
                        return response.text().then(text => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(text, 'text/html');
                            const redirectUrl = doc.querySelector('meta[http-equiv="refresh"]')?.getAttribute('content');
                            if (redirectUrl) {
                                const url = redirectUrl.split('url=')[1];
                                window.location.href = url;
                            } else {
                                window.location.reload();
                            }
                        });
                    }
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Terjadi kesalahan saat memproses pesanan');
                    });
                }
            })
            .then(data => {
                if (data && data.redirect) {
                    window.location.href = data.redirect;
                } else if (data && data.order_number) {
                    // Redirect to payment page instead of order status
                    window.location.href = `/payment/${data.order_number}`;
                } else {
                    // Fallback: reload page if no specific redirect
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showAlert(error.message || 'Terjadi kesalahan saat memproses pesanan');
            })
            .finally(() => {
                this.isSubmitting = false;
            });
        }
    }
}
</script>
@endpush

@push('styles')
<style>
[x-cloak] { display: none !important; }
</style>
@endpush
@endsection

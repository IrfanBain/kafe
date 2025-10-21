@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 001.414 1.414L2 12.414V19a1 1 0 001 1h3a1 1 0 001-1v-3a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 001 1h3a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-9-9z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('category.show', $product->category) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="relative">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-96 lg:h-full object-cover">
                    @else
                        <div class="w-full h-96 lg:h-full bg-gray-200 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-400 text-lg">No Image</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    @if($product->is_available)
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Tersedia
                            </span>
                        </div>
                    @else
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Tidak Tersedia
                            </span>
                        </div>
                    @endif

                    <!-- Stock Badge -->
                    @if($product->stock !== null)
                        <div class="absolute top-4 right-4">
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Stok: {{ $product->stock }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-8">
                    <!-- Category -->
                    <div class="mb-4">
                        <a href="{{ route('category.show', $product->category) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition duration-200">
                            {{ $product->category->name }}
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Product Name -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        {{ $product->name }}
                    </h1>

                    <!-- Price -->
                    <div class="mb-6">
                        <span class="text-3xl md:text-4xl font-bold text-blue-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $product->description ?: 'Tidak ada deskripsi tersedia untuk produk ini.' }}
                        </p>
                    </div>

                    <!-- Product Details -->
                    <div class="border-t border-gray-200 pt-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Produk</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kategori:</span>
                                <span class="font-medium text-gray-900">{{ $product->category->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium {{ $product->is_available ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                            @if($product->stock !== null)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Stok:</span>
                                <span class="font-medium text-gray-900">{{ $product->stock }} tersisa</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <button onclick="showOrderInfoModal()" 
                                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 {{ !$product->is_available ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$product->is_available ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13l2.5 2.5m6-7h.01M19 10h.01"></path>
                            </svg>
                            {{ $product->is_available ? 'Pesan Sekarang' : 'Tidak Tersedia' }}
                        </button>
                        
                        <div class="flex space-x-4">
                            <button onclick="shareProduct()" class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition duration-300">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                Bagikan
                            </button>
                            <a href="{{ route('category.show', $product->category) }}" class="w-full bg-blue-100 text-blue-700 py-2 px-4 rounded-lg font-medium hover:bg-blue-200 transition duration-300 text-center">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Kategori
                            </a>
                        </div>
                    </div>

                    <!-- QR Code Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-blue-900">Cara Memesan</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    Scan QR code yang tersedia di meja Anda untuk mengakses menu lengkap dan melakukan pemesanan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">
            Produk Serupa
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <a href="{{ route('product.show', $relatedProduct) }}" class="block">
                        @if($relatedProduct->image)
                            <img src="{{ Storage::url($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $relatedProduct->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($relatedProduct->description, 60) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-blue-600">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</span>
                                @if($relatedProduct->is_available)
                                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">Tersedia</span>
                                @else
                                    <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded">Habis</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Order Info Modal -->
<div id="orderInfoModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center p-4 z-50" style="display: none;">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95" id="modalContent">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Cara Memesan</h3>
                </div>
                <button onclick="closeOrderInfoModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="space-y-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mt-0.5">
                            1
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-1">Scan QR Code</h4>
                            <p class="text-blue-700 text-sm">Cari dan scan QR code yang tersedia di meja Anda untuk mengakses menu digital.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <div class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mt-0.5">
                            2
                        </div>
                        <div>
                            <h4 class="font-semibold text-green-900 mb-1">Pilih Menu</h4>
                            <p class="text-green-700 text-sm">Tambahkan produk yang diinginkan ke keranjang belanja Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 p-4 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <div class="bg-purple-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mt-0.5">
                            3
                        </div>
                        <div>
                            <h4 class="font-semibold text-purple-900 mb-1">Konfirmasi & Bayar</h4>
                            <p class="text-purple-700 text-sm">Isi data diri, konfirmasi pesanan, dan lakukan pembayaran digital.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex space-x-3">
                <button onclick="closeOrderInfoModal()" class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition duration-300">
                    Mengerti
                </button>
                <button onclick="showQRHelp()" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                    Bantuan QR
                </button>
            </div>

            <!-- QR Help Info -->
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-yellow-800 text-sm">
                        <strong>Tips:</strong> QR code biasanya terletak di atas meja atau ditempel pada dinding dekat meja Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showOrderInfoModal() {
    const modal = document.getElementById('orderInfoModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.style.display = 'flex';
    
    // Animate modal appearance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
    }, 50);
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeOrderInfoModal() {
    const modal = document.getElementById('orderInfoModal');
    const modalContent = document.getElementById('modalContent');
    
    // Animate modal disappearance
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }, 200);
}

function showQRHelp() {
    alert('Jika Anda tidak menemukan QR code di meja, silakan tanyakan kepada pelayan kami untuk bantuan lebih lanjut.');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('orderInfoModal');
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeOrderInfoModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeOrderInfoModal();
        }
    });
});

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }}',
            text: '{{ $product->description }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Link produk telah disalin ke clipboard!');
        });
    }
}
</script>
@endsection
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 text-white">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-bounce delay-1000"></div>
    <div class="absolute top-32 right-20 w-16 h-16 bg-white/5 rounded-full animate-pulse"></div>
    <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white/10 rounded-full animate-bounce delay-500"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center animate-fade-in">
            <h1 class="text-5xl md:text-7xl font-bold mb-8 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                Selamat Datang di {{ $storeSettings['store_name'] }}
            </h1>
            <p class="text-xl md:text-2xl mb-12 opacity-90 max-w-3xl mx-auto leading-relaxed">
                {{ $storeSettings['store_description'] ?: 'Nikmati pengalaman kuliner terbaik dengan sistem pemesanan digital yang modern dan mudah digunakan' }}
            </p>
            <div class="space-y-4 sm:space-y-0 sm:space-x-6 sm:flex sm:justify-center">
                <a href="#menu" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 hover:scale-105 transition duration-300 shadow-lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Lihat Menu
                </a>
                <a href="#about" class="inline-block border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-blue-600 hover:scale-105 transition duration-300">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tentang Kami
                </a>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</div>


<!-- Featured Products -->
<section id="menu" class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-semibold uppercase tracking-wide">Menu Terbaik</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 mt-4">
                Menu Unggulan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Cicipi hidangan spesial kami yang dibuat dengan bahan-bahan berkualitas tinggi dan resep rahasia turun temurun
            </p>
        </div>

        @if($featuredProducts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <a href="{{ route('product.show', $product) }}" class="block">
                            <div class="relative overflow-hidden bg-gray-100">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-56 object-cover transition duration-500 hover:scale-105" loading="lazy">
                                @else
                                    <div class="w-full h-56 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-gray-400 text-sm">No Image</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Quick View Button -->
                                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="bg-white/90 backdrop-blur-sm text-gray-700 p-2 rounded-full shadow-lg hover:bg-white transition duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-xl text-gray-900 mb-3 group-hover:text-blue-600 transition duration-200">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ Str::limit($product->description, 80) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <div class="flex items-center text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-gray-500 text-sm ml-1">(4.8)</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <!-- View More Button -->
            <div class="text-center mt-12">
                <a href="{{ route('categories.index') }}" class="inline-flex items-center bg-blue-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-blue-700 hover:scale-105 transition duration-300 shadow-lg">
                    <span>Lihat Semua Menu</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Menu</h3>
                <p class="text-gray-600">Menu yang tersedia akan segera ditampilkan di sini.</p>
            </div>
        @endif
    </div>
</section>

<!-- Categories -->
<section id="categories" class="py-20 bg-gray-900 text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-600 to-purple-600"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold uppercase tracking-wide">Eksplorasi</span>
            <h2 class="text-4xl md:text-5xl font-bold mb-6 mt-4">
                Kategori Menu
            </h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Jelajahi berbagai kategori menu yang kami sediakan dengan cita rasa autentik dan kualitas terbaik
            </p>
        </div>

        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('category.show', $category) }}" class="group relative bg-white/10 backdrop-blur-sm rounded-3xl overflow-hidden hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-2 block">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        
                        <div class="relative p-8">
                            <div class="relative overflow-hidden rounded-2xl mb-6">
                                @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="text-gray-300 text-sm">No Image</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Category Badge -->
                                <div class="absolute top-4 left-4">
                                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $category->products->count() }} Items
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="font-bold text-2xl mb-4 group-hover:text-blue-300 transition duration-200">{{ $category->name }}</h3>
                            <p class="text-gray-300 mb-6 leading-relaxed">{{ $category->description ?: 'Nikmati berbagai pilihan menu terbaik dalam kategori ini.' }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-blue-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    <span class="font-semibold">{{ $category->products->count() }} menu tersedia</span>
                                </div>
                                
                                <div class="opacity-0 group-hover:opacity-100 transition duration-300">
                                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- View All Categories Button -->
            <div class="text-center mt-12">
                <a href="{{ route('categories.index') }}" class="inline-flex items-center bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 hover:scale-105 transition duration-300 shadow-lg">
                    <span>Lihat Semua Kategori</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-xl font-semibold mb-2">Belum Ada Kategori</h3>
                <p class="text-gray-400">Kategori menu akan segera tersedia.</p>
            </div>
        @endif
    </div>
</section>


<!-- About Section -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="bg-green-100 text-green-600 px-4 py-2 rounded-full text-sm font-semibold uppercase tracking-wide">Tentang Kami</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 mt-4">
                Pengalaman Digital Terdepan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Kami menghadirkan revolusi dalam dunia kuliner dengan teknologi digital yang memudahkan setiap pengalaman bersantap Anda
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <!-- Features List -->
            <div class="space-y-8">
                <div class="flex items-start space-x-6 group">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 text-blue-600 p-4 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl text-gray-900 mb-3">Pesan via QR Code</h4>
                        <p class="text-gray-600 leading-relaxed">Scan QR code di meja Anda untuk mengakses menu digital lengkap dan memesan langsung tanpa perlu menunggu pelayan.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-6 group">
                    <div class="flex-shrink-0">
                        <div class="bg-green-100 text-green-600 p-4 rounded-2xl group-hover:bg-green-600 group-hover:text-white transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl text-gray-900 mb-3">Pembayaran Digital</h4>
                        <p class="text-gray-600 leading-relaxed">Nikmati kemudahan pembayaran dengan berbagai metode digital yang aman, cepat, dan terpercaya.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-6 group">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 text-purple-600 p-4 rounded-2xl group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl text-gray-900 mb-3">Pelayanan Cepat</h4>
                        <p class="text-gray-600 leading-relaxed">Dapatkan update real-time status pesanan Anda dan nikmati pelayanan yang efisien tanpa menunggu lama.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-6 group">
                    <div class="flex-shrink-0">
                        <div class="bg-yellow-100 text-yellow-600 p-4 rounded-2xl group-hover:bg-yellow-600 group-hover:text-white transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl text-gray-900 mb-3">Pengalaman Personal</h4>
                        <p class="text-gray-600 leading-relaxed">Setiap pesanan dicatat dengan detail untuk memberikan pengalaman yang personal dan berkesan.</p>
                    </div>
                </div>
            </div>

            <!-- How to Order -->
            <div class="bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-50 p-10 rounded-3xl shadow-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">Cara Memesan</h3>
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold shadow-lg">1</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Scan QR Code</h4>
                            <p class="text-gray-600 text-sm">Temukan dan scan QR code di meja Anda</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold shadow-lg">2</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Pilih Menu</h4>
                            <p class="text-gray-600 text-sm">Jelajahi menu dan tambahkan ke keranjang</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold shadow-lg">3</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Isi Data Diri</h4>
                            <p class="text-gray-600 text-sm">Masukkan nama dan konfirmasi pesanan</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold shadow-lg">4</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Bayar Digital</h4>
                            <p class="text-gray-600 text-sm">Pilih metode pembayaran digital favorit</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold shadow-lg">✓</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Pesanan Siap!</h4>
                            <p class="text-gray-600 text-sm">Tunggu pesanan Anda dengan santai</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 p-4 bg-blue-100 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-blue-800 text-sm font-medium">
                            <strong>Tips:</strong> Pastikan koneksi internet stabil untuk pengalaman terbaik!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats and Trust Indicators -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-16 border-t border-gray-200">
            <div class="text-center">
                <div class="bg-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-lg text-gray-900">Terpercaya</h4>
                <p class="text-gray-600 text-sm">Sistem keamanan tinggi</p>
            </div>
            
            <div class="text-center">
                <div class="bg-green-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-lg text-gray-900">Cepat</h4>
                <p class="text-gray-600 text-sm">Proses dalam hitungan menit</p>
            </div>
            
            <div class="text-center">
                <div class="bg-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-lg text-gray-900">Modern</h4>
                <p class="text-gray-600 text-sm">Teknologi terdepan</p>
            </div>
            
            <div class="text-center">
                <div class="bg-yellow-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-lg text-gray-900">Ramah</h4>
                <p class="text-gray-600 text-sm">Interface yang mudah</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About -->
            <div class="lg:col-span-2">
                <h3 class="text-2xl font-bold mb-6">{{ $storeSettings['store_name'] }}</h3>
                <p class="text-gray-300 mb-6 leading-relaxed max-w-md">
                    {{ $storeSettings['store_description'] ?: 'Nikmati pengalaman kuliner digital terdepan dengan sistem pemesanan QR code yang modern, pembayaran digital yang aman, dan pelayanan berkualitas tinggi.' }}
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="bg-gray-800 hover:bg-blue-600 p-3 rounded-full transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-gray-800 hover:bg-blue-600 p-3 rounded-full transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-gray-800 hover:bg-blue-600 p-3 rounded-full transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.347-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-gray-800 hover:bg-blue-600 p-3 rounded-full transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.007 0C5.383 0 0 5.383 0 12.007s5.383 12.007 12.007 12.007 12.007-5.383 12.007-12.007S18.631.001 12.007.001zM8.547 18.752c-.297 0-.534-.238-.534-.534V9.542c0-.297.237-.534.534-.534h.712c.297 0 .534.237.534.534v8.676c0 .296-.237.534-.534.534H8.547zM9.19 8.464c-.593 0-1.073-.48-1.073-1.073s.48-1.073 1.073-1.073 1.073.48 1.073 1.073-.48 1.073-1.073 1.073zM18.75 18.752h-.712c-.297 0-.534-.238-.534-.534v-4.27c0-.89-.712-1.602-1.602-1.602s-1.602.712-1.602 1.602v4.27c0 .296-.237.534-.534.534h-.712c-.297 0-.534-.238-.534-.534V9.542c0-.297.237-.534.534-.534h.712c.297 0 .534.237.534.534v.623c.475-.593 1.208-.979 2.136-.979 1.781 0 3.214 1.432 3.214 3.214v5.352c0 .296-.237.534-.534.534z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-6">Menu Cepat</h4>
                <ul class="space-y-3">
                    <li><a href="#menu" class="text-gray-300 hover:text-white transition duration-200">Menu Unggulan</a></li>
                    <li><a href="#categories" class="text-gray-300 hover:text-white transition duration-200">Kategori</a></li>
                    <li><a href="#about" class="text-gray-300 hover:text-white transition duration-200">Tentang Kami</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Cara Pesan</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-6">Kontak</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-gray-300 text-sm">Jl. Digital Street No. 123, Jakarta</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-300 text-sm">+62 812-3456-7890</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-300 text-sm">hello@kafe.com</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-300 text-sm">Buka: 08:00 - 22:00 WIB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-400 text-sm mb-4 md:mb-0">
                © {{ date('Y') }} {{ $storeSettings['store_name'] }}. Semua hak dilindungi.
            </div>
            <div class="flex space-x-6 text-sm">
                <a href="#" class="text-gray-400 hover:text-white transition duration-200">Kebijakan Privasi</a>
                <a href="#" class="text-gray-400 hover:text-white transition duration-200">Syarat & Ketentuan</a>
                <a href="#" class="text-gray-400 hover:text-white transition duration-200">FAQ</a>
            </div>
        </div>
    </div>
</footer>

<!-- Custom Styles and Scripts -->
<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.counter {
    transition: all 0.5s ease;
}
</style>

<script>
// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });
}

// Intersection Observer for counter animation
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounters();
            observer.unobserve(entry.target);
        }
    });
});

// Observe the statistics section
document.addEventListener('DOMContentLoaded', function() {
    const statsSection = document.querySelector('.counter').closest('section');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection

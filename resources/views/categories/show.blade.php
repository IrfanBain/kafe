@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 001.414 1.414L2 12.414V19a1 1 0 001 1h3a1 1 0 001-1v-3a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 001 1h3a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-9-9z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('categories.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Kategori</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Category Header -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        {{ $category->name }}
                    </h1>
                    <p class="text-xl md:text-2xl opacity-90 mb-6">
                        {{ $category->description ?: 'Temukan berbagai pilihan menu terbaik dalam kategori ini dengan cita rasa yang autentik dan kualitas premium.' }}
                    </p>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-lg font-semibold">{{ $products->total() }} Menu</span>
                        </div>
                        @if($products->count() > 0)
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="text-lg">Mulai Rp {{ number_format($products->min('price'), 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($category->image)
                <div class="hidden lg:block">
                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-full h-64 object-cover rounded-2xl shadow-2xl">
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari menu dalam {{ $category->name }}..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="flex gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Harga:</label>
                    <input 
                        type="number" 
                        name="min_price" 
                        value="{{ request('min_price') }}"
                        placeholder="Min" 
                        min="{{ $priceRange['min'] }}"
                        max="{{ $priceRange['max'] }}"
                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <span class="text-gray-500">-</span>
                    <input 
                        type="number" 
                        name="max_price" 
                        value="{{ request('max_price') }}"
                        placeholder="Max" 
                        min="{{ $priceRange['min'] }}"
                        max="{{ $priceRange['max'] }}"
                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Sort -->
                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>

                <!-- Filter Button -->
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    Filter
                </button>

                <!-- Reset Button -->
                @if(request()->hasAny(['search', 'min_price', 'max_price', 'sort']))
                    <a href="{{ route('category.show', $category) }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition duration-200">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($products->count() > 0)
            <!-- Results Info -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Menampilkan {{ $products->count() }} dari {{ $products->total() }} menu
                    </h2>
                    @if(request()->hasAny(['search', 'min_price', 'max_price']))
                        <p class="text-gray-600 mt-1">
                            Hasil pencarian 
                            @if(request('search'))
                                untuk "{{ request('search') }}"
                            @endif
                            @if(request('min_price') || request('max_price'))
                                dengan harga 
                                @if(request('min_price') && request('max_price'))
                                    Rp {{ number_format(request('min_price'), 0, ',', '.') }} - Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                                @elseif(request('min_price'))
                                    di atas Rp {{ number_format(request('min_price'), 0, ',', '.') }}
                                @else
                                    di bawah Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                                @endif
                            @endif
                        </p>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
                        <a href="{{ route('product.show', $product) }}" class="block">
                            <div class="relative overflow-hidden bg-gray-100">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover transition duration-500 hover:scale-105" loading="lazy">
                                @else
                                    <div class="w-full h-56 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-gray-400 text-sm">{{ $product->name }}</span>
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

                                <!-- Stock Badge -->
                                @if($product->stock !== null && $product->stock <= 5)
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold shadow-lg">
                                            Stok Terbatas
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-blue-600 transition duration-200">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                                
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xl font-bold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <div class="flex items-center text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-gray-500 text-sm ml-1">(4.8)</span>
                                    </div>
                                </div>

                                <!-- Stock Info -->
                                @if($product->stock !== null)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Stok tersisa:</span>
                                    <span class="font-medium {{ $product->stock <= 5 ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $product->stock }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.766 0-5.227-1.116-6.953-2.927.022-.15.05-.3.083-.45A8.013 8.013 0 0112 9c.996 0 1.951.194 2.828.565M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Menu Ditemukan</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['search', 'min_price', 'max_price']))
                        Coba ubah filter atau kata kunci pencarian Anda.
                    @else
                        Belum ada menu yang tersedia dalam kategori ini.
                    @endif
                </p>
                <div class="flex justify-center space-x-4">
                    @if(request()->hasAny(['search', 'min_price', 'max_price']))
                    <a href="{{ route('category.show', $category) }}" class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('categories.index') }}" class="inline-flex items-center bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Lihat Kategori Lain
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
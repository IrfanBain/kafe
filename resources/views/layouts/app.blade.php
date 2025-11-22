<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $storeSettings['store_name'] }} - @yield('title', 'Home')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 text-xl font-bold text-gray-900">
                            @if(!empty($storeSettings['store_logo']) && $storeSettings['store_logo'])
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-8 w-8 object-cover rounded">
                            @else
                                🍽️
                            @endif
                            <span>{{ $storeSettings['store_name'] }}</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Home
                        </a>
                        @auth
                            <a href="/admin" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                Admin Panel
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} {{ $storeSettings['store_name'] }}. All rights reserved.</p>
                    @if(!empty($storeSettings['store_address']))
                        <p class="text-sm text-gray-400 mt-1">{{ $storeSettings['store_address'] }}</p>
                    @endif
                    @if(!empty($storeSettings['store_phone']))
                        <p class="text-sm text-gray-400">Tel: {{ $storeSettings['store_phone'] }}</p>
                    @endif
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Store Information
        </x-slot>

        @php
            $storeInfo = $this->getStoreInfo();
        @endphp

        <div class="space-y-4">
            <div class="flex items-center gap-x-4">
                @if($storeInfo['logo_url'])
                    <img src="{{ $storeInfo['logo_url'] }}" alt="Store Logo" class="w-16 h-16 rounded-lg object-cover">
                @else
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                        <x-heroicon-o-building-storefront class="w-8 h-8 text-gray-400" />
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $storeInfo['name'] }}</h3>
                    @if($storeInfo['description'])
                        <p class="text-sm text-gray-600">{{ $storeInfo['description'] }}</p>
                    @endif
                </div>
            </div>

            <div class="space-y-2 text-sm">
                @if($storeInfo['address'])
                    <div class="flex items-center gap-x-2">
                        <x-heroicon-o-map-pin class="w-4 h-4 text-gray-400 mr-2" />
                        <span class="text-gray-600">{{ $storeInfo['address'] }}</span>
                    </div>
                @endif

                @if($storeInfo['phone'])
                    <div class="flex items-center gap-x-2">
                        <x-heroicon-o-phone class="w-4 h-4 text-gray-400 mr-2" />
                        <span class="text-gray-600">{{ $storeInfo['phone'] }}</span>
                    </div>
                @endif

                @if($storeInfo['email'])
                    <div class="flex items-center gap-x-2">
                        <x-heroicon-o-envelope class="w-4 h-4 text-gray-400 mr-2" />
                        <span class="text-gray-600">{{ $storeInfo['email'] }}</span>
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t">
                <div class="text-xs text-gray-500">
                    <div class="flex items-center justify-between">
                        <span>Current Time:</span>
                        <span>{{ now()->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span>Cashier Session:</span>
                        <span class="text-green-600">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
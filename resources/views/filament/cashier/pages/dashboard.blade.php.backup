<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Quick Actions -->
        <div class="col-span-full">
            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('filament.cashier.pages.create-order-page') }}"
                   class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg text-center transition-colors">
                    <div class="text-2xl mb-2">🛒</div>
                    <div class="font-semibold">New Order</div>
                </a>

                <a href="{{ route('filament.cashier.resources.orders.index') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition-colors">
                    <div class="text-2xl mb-2">📋</div>
                    <div class="font-semibold">View Orders</div>
                </a>

                <a href="{{ route('cashier.dashboard') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg text-center transition-colors">
                    <div class="text-2xl mb-2">⚙️</div>
                    <div class="font-semibold">Regular Dashboard</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Widgets will be rendered here -->
    <div class="space-y-6">
        @foreach ($this->getWidgets() as $widget)
            @livewire($widget)
        @endforeach
    </div>
</x-filament-panels::page>

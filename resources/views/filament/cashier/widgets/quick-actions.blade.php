<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('cashier.create-order') }}" class="block p-6 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 bg-primary-500 rounded-lg">
                        <x-heroicon-o-plus-circle class="w-6 h-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-primary-900">Create New Order</h3>
                        <p class="text-primary-600">Start a new customer order</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('cashier.order-history') }}" class="block p-6 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-500 rounded-lg">
                        <x-heroicon-o-clock class="w-6 h-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Order History</h3>
                        <p class="text-gray-600">View all previous orders</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('cashier.dashboard') }}" class="block p-6 bg-success-50 border border-success-200 rounded-lg hover:bg-success-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 bg-success-500 rounded-lg">
                        <x-heroicon-o-computer-desktop class="w-6 h-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-success-900">POS Dashboard</h3>
                        <p class="text-success-600">Full cashier interface</p>
                    </div>
                </div>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
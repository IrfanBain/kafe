@extends('layouts.app')

@section('title', 'Status Pesanan - ' . $order->order_number)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Status Pesanan</h1>
                <p class="text-gray-600">{{ $order->order_number }}</p>
            </div>
            <div class="text-right">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'preparing' => 'bg-orange-100 text-orange-800',
                        'ready' => 'bg-green-100 text-green-800',
                        'served' => 'bg-green-100 text-green-800',
                        'completed' => 'bg-gray-100 text-gray-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];

                    $paymentStatusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-gray-100 text-gray-800'
                    ];
                @endphp

                <div class="space-y-2">
                    <div class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        Status: {{ ucfirst($order->status) }}
                    </div>
                    <div class="px-3 py-1 rounded-full text-xs font-medium {{ $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                        Pembayaran: {{ ucfirst($order->payment_status) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Nama:</span>
                <span class="font-medium ml-2">{{ $order->customer_name }}</span>
            </div>
            <div>
                <span class="text-gray-600">Meja:</span>
                <span class="font-medium ml-2">{{ $order->table ? $order->table->number : 'Take Away' }}</span>
            </div>
            <div>
                <span class="text-gray-600">Waktu Pesan:</span>
                <span class="font-medium ml-2">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
            </div>
            <div>
                <span class="text-gray-600">Total:</span>
                <span class="font-medium ml-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Order Progress -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Progress Pesanan</h2>

        <div class="space-y-4">
            @php
                $steps = [
                    'pending' => ['label' => 'Pesanan Diterima', 'icon' => '📝'],
                    'confirmed' => ['label' => 'Pesanan Dikonfirmasi', 'icon' => '✓'],
                    'preparing' => ['label' => 'Sedang Diproses', 'icon' => '👨‍🍳'],
                    'ready' => ['label' => 'Siap Disajikan', 'icon' => '🍽️'],
                    'served' => ['label' => 'Pesanan Diantarkan', 'icon' => '✨'],
                ];

                $currentStepIndex = array_search($order->status, array_keys($steps));
            @endphp

            @foreach($steps as $status => $step)
                @php
                    $stepIndex = array_search($status, array_keys($steps));
                    $isCompleted = $stepIndex <= $currentStepIndex;
                    $isCurrent = $status === $order->status;
                @endphp

                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isCompleted ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                            @if($isCompleted)
                                {{ $step['icon'] }}
                            @else
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium {{ $isCompleted ? 'text-gray-900' : 'text-gray-500' }}">
                            {{ $step['label'] }}
                        </p>
                        @if($isCurrent)
                            <p class="text-sm text-blue-600 font-medium">Status saat ini</p>
                        @endif
                    </div>
                    @if($isCompleted)
                        <div class="text-green-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pesanan</h2>

        <div class="space-y-4">
            @foreach($order->orderItems as $item)
                <div class="flex items-center space-x-4 py-3 border-b border-gray-100 last:border-b-0">
                    @if($item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-400 text-xs">No Image</span>
                        </div>
                    @endif

                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                        @if($item->notes)
                            <p class="text-xs text-gray-500 mt-1">Catatan: {{ $item->notes }}</p>
                        @endif
                    </div>

                    <div class="text-right">
                        <p class="font-medium text-gray-900">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach

            @if($order->notes)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4">
                    <p class="text-sm text-yellow-800">
                        <span class="font-medium">Catatan Pesanan:</span> {{ $order->notes }}
                    </p>
                </div>
            @endif

            <div class="border-t pt-4 mt-4">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>Total</span>
                    <span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    @if($order->transaction)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Pembayaran</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">ID Transaksi:</span>
                    <span class="font-medium ml-2">{{ $order->transaction->transaction_id }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Metode:</span>
                    <span class="font-medium ml-2">{{ ucfirst(str_replace('_', ' ', $order->transaction->payment_method)) }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Waktu Bayar:</span>
                    <span class="font-medium ml-2">
                        @php
                            try {
                                if ($order->transaction->paid_at && $order->transaction->paid_at instanceof \Carbon\Carbon) {
                                    echo $order->transaction->paid_at->format('d/m/Y H:i:s');
                                } else {
                                    echo '<span class="text-gray-400">Belum dibayar</span>';
                                }
                            } catch (Exception $e) {
                                echo '<span class="text-gray-400">Format tanggal tidak valid</span>';
                            }
                        @endphp
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium ml-2 px-2 py-1 rounded text-xs {{ $paymentStatusColors[$order->transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($order->transaction->status) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="space-y-4">
        @if($order->payment_status === 'pending' && $snapToken)
            <button
                id="pay-button"
                class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold text-center hover:bg-blue-700 transition duration-200"
            >
                Lanjutkan Pembayaran
            </button>
        @elseif($order->payment_status === 'pending')
            <div class="block w-full bg-red-100 text-red-700 py-3 px-4 rounded-lg text-center">
                Pembayaran tidak tersedia. Silakan hubungi staff.
            </div>
        @endif

        <button
            onclick="window.location.reload()"
            class="block w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-200"
        >
            Refresh Status
        </button>

        <a
            href="{{ route('home') }}"
            class="block w-full text-center text-gray-600 py-2 hover:text-gray-800 transition duration-200"
        >
            Kembali ke Beranda
        </a>
    </div>

    <!-- Auto Refresh -->
    @if(in_array($order->status, ['confirmed', 'preparing']))
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                Halaman ini akan otomatis refresh setiap 30 detik
            </p>
        </div>
    @endif
</div>

@if(in_array($order->status, ['confirmed', 'preparing']))
@push('scripts')
<script>
// Auto refresh every 30 seconds for active orders
setTimeout(function() {
    window.location.reload();
}, 30000);
</script>
@endpush
@endif

@if($order->payment_status === 'pending' && $snapToken)
@push('scripts')
<!-- Midtrans Snap JS -->
@if(config('services.midtrans.is_production'))
<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@else
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endif

<script type="text/javascript">
document.getElementById('pay-button').onclick = function(){
    // SnapToken acquired from previous step
    console.log('Snap Token:', '{{ $snapToken }}');
    snap.pay('{{ $snapToken }}', {
        // Optional
        onSuccess: function(result){
            /* You may add your own js here, this is just example */
            // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            console.log('Payment success:', result);

            // Show success message
            alert('Pembayaran berhasil! Pesanan Anda sedang diproses.');

            // Redirect to success page or reload current page
            window.location.href = "{{ route('payment.success', $order->order_number) }}";
        },
        // Optional
        onPending: function(result){
            /* You may add your own js here, this is just example */
            // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            console.log('Payment pending:', result);

            // Show pending message
            alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');

            // Reload page to check status
            setTimeout(function() {
                window.location.reload();
            }, 3000);
        },
        // Optional
        onError: function(result){
            /* You may add your own js here, this is just example */
            // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            console.log('Payment error:', result);

            // Show error message
            alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        },
        onClose: function(){
            /* You may add your own implementation here */
            console.log('Customer closed the popup without finishing the payment');
        }
    });
};
</script>
@endpush
@endif
@endsection

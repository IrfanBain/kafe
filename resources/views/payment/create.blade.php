@extends('layouts.app')

@section('title', 'Pembayaran - ' . $order->order_number)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Order Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Pembayaran</h1>

        <div class="border-b pb-4 mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Nomor Pesanan:</span>
                <span class="font-semibold">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Nama:</span>
                <span class="font-semibold">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Meja:</span>
                <span class="font-semibold">{{ $order->table->number }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Waktu Pesanan:</span>
                <span class="font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- Order Items -->
        <div class="space-y-3 mb-4">
            <h3 class="font-semibold text-gray-900">Detail Pesanan:</h3>
            @foreach($order->orderItems as $item)
                <div class="flex justify-between items-center text-sm">
                    <div>
                        <span class="font-medium">{{ $item->product->name }}</span>
                        <span class="text-gray-500 ml-2">x{{ $item->quantity }}</span>
                    </div>
                    <span class="font-medium">Rp {{ number_format((float)$item->total, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t pt-4">
            <div class="flex justify-between items-center text-lg font-bold">
                <span>Total Pembayaran:</span>
                <span class="text-blue-600">Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>

        @if($snapToken)
            <div class="space-y-4 mb-6">
                <p class="text-gray-600">Silakan klik tombol di bawah untuk melanjutkan ke halaman pembayaran Midtrans.</p>
            </div>

            <div class="space-y-4">
                <button
                    id="pay-button"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-200"
                >
                    Bayar Sekarang - Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}
                </button>

                <a
                    href="{{ route('order.status', $order->order_number) }}"
                    class="block w-full text-center border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-200"
                >
                    Kembali ke Status Pesanan
                </a>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <span class="text-red-600 text-xl mr-2">⚠️</span>
                    <div class="text-red-800">
                        <p class="font-medium">Terjadi kesalahan dalam memproses pembayaran</p>
                        <p class="text-sm">Silakan hubungi staff atau coba lagi nanti.</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a
                        href="{{ route('order.status', $order->order_number) }}"
                        class="inline-block bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-200"
                    >
                        Kembali ke Status Pesanan
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Payment Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
        <div class="flex items-start space-x-3">
            <span class="text-blue-600 text-xl">ℹ️</span>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Informasi Pembayaran:</p>
                <ul class="space-y-1 text-blue-700">
                    <li>• Pembayaran akan diproses secara real-time</li>
                    <li>• Anda akan menerima konfirmasi setelah pembayaran berhasil</li>
                    <li>• Pesanan akan segera diproses setelah pembayaran dikonfirmasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($snapToken)
<!-- Include Midtrans Snap.js -->
<script type="text/javascript"
        src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script type="text/javascript">
document.getElementById('pay-button').onclick = function(){
    // SnapToken acquired from previous step
    snap.pay('{{ $snapToken }}', {
        // Optional
        onSuccess: function(result){
            /* You may add your own js here, this is just example */
            console.log('Payment success:', result);
            window.location.href = '{{ route("order.status", parameters: $order->order_number) }}';
        },
        // Optional
        onPending: function(result){
            /* You may add your own js here, this is just example */
            console.log('Payment pending:', result);
            window.location.href = '{{ route("order.status", $order->order_number) }}';
        },
        // Optional
        onError: function(result){
            /* You may add your own js here, this is just example */
            console.log('Payment error:', result);
            alert('Pembayaran gagal. Silakan coba lagi.');
        }
    });
};
</script>
@endif
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 text-2xl">✓</span>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-4">Pembayaran Berhasil!</h1>
        <p class="text-gray-600 mb-8">Terima kasih atas pembayaran Anda. Pesanan sedang diproses.</p>

        <!-- Transaction Details -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
            <h3 class="font-semibold text-gray-900 mb-4">Detail Transaksi</h3>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Pesanan:</span>
                    <span class="font-medium">{{ $order->order_number }}</span>
                </div>

                @if($order->transaction)
                <div class="flex justify-between">
                    <span class="text-gray-600">ID Transaksi:</span>
                    <span class="font-medium">{{ $order->transaction->transaction_id }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Metode Pembayaran:</span>
                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->transaction->payment_method)) }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu Pembayaran:</span>
                    <span class="font-medium">
                        @php
                            try {
                                echo $order->transaction && $order->transaction->paid_at
                                    ? $order->transaction->paid_at->format('d/m/Y H:i:s')
                                    : 'Belum dibayar';
                            } catch (Exception $e) {
                                echo 'Belum dibayar';
                            }
                        @endphp
                    </span>
                </div>
                @endif

                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Pelanggan:</span>
                    <span class="font-medium">{{ $order->customer_name }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Meja:</span>
                    <span class="font-medium">
                        @php
                            try {
                                echo $order->table ? $order->table->number : 'Take Away';
                            } catch (Exception $e) {
                                echo 'Take Away';
                            }
                        @endphp
                    </span>
                </div>

                <div class="border-t pt-2 mt-4">
                    <div class="flex justify-between font-semibold">
                        <span>Total Dibayar:</span>
                        <span class="text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex items-center justify-center space-x-2 text-blue-800">
                <span class="text-xl">ℹ️</span>
                <div class="text-sm">
                    <p class="font-medium">Status Pesanan: {{ ucfirst($order->status) }}</p>
                    <p class="mt-1">Pesanan Anda sedang diproses oleh dapur. Anda akan mendapatkan notifikasi saat pesanan siap.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a
                href="{{ route('order.status', $order->order_number) }}"
                class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-200"
            >
                Lihat Status Pesanan
            </a>

            <a
                href="{{ route('home') }}"
                class="block w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-200"
            >
                Kembali ke Beranda
            </a>
        </div>

        <!-- Receipt Info -->
        <div class="mt-8 text-xs text-gray-500">
            <p>Simpan halaman ini sebagai bukti pembayaran</p>
            <p>Untuk pertanyaan, hubungi staff kafe</p>
        </div>
    </div>
</div>
@endsection

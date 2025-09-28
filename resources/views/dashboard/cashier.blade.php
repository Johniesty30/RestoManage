<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cashier Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg">Selamat Datang, Kasir!</h3>

                    <div class="mt-4">
                        <h4 class="font-semibold">Statistik Keuangan:</h4>
                        <ul>
                            <li>Pendapatan Hari Ini: Rp {{ number_format($todayRevenue, 0, ',', '.') }}</li>
                            <li>Pendapatan Bulan Ini: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <h4 class="font-semibold">Pesanan Menunggu Pembayaran:</h4>
                        @forelse($pendingPayments as $order)
                            <p>Pesanan #{{ $order->order_id }} oleh {{ $order->customer->name }} - Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        @empty
                            <p>Tidak ada pesanan yang menunggu pembayaran.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

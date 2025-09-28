<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p>Poin loyalitas Anda saat ini: <strong>{{ $loyaltyPoints }} poin</strong>.</p>

                    <div class="mt-4">
                        <h4 class="font-semibold">5 Pesanan Terakhir Anda:</h4>
                        @forelse($recentOrders as $order)
                           <p>Order #{{$order->order_id}} - {{ \Carbon\Carbon::parse($order->order_time)->format('d M Y') }} - Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        @empty
                            <p>Anda belum memiliki riwayat pesanan.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        <h4 class="font-semibold">Reservasi Anda yang Akan Datang:</h4>
                        @forelse($upcomingReservations as $reservation)
                            <p>Reservasi untuk tanggal {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('d M Y, H:i') }} di Meja {{ $reservation->table->table_number }}</p>
                        @empty
                            <p>Anda tidak memiliki reservasi yang akan datang.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

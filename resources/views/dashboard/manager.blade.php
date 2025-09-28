<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg">Selamat Datang, Manajer!</h3>
                    <p>Ini adalah halaman dashboard khusus untuk manajer.</p>

                    <!-- Tampilkan data statistik -->
                    <div class="mt-4">
                        <h4 class="font-semibold">Statistik Hari Ini:</h4>
                        <ul>
                            <li>Pesanan Hari Ini: {{ $stats['today_orders'] }}</li>
                            <li>Reservasi Hari Ini: {{ $stats['today_reservations'] }}</li>
                            <li>Meja Tersedia: {{ $stats['available_tables'] }}</li>
                            <li>Pendapatan Hari Ini: Rp {{ number_format($stats['total_revenue_today'], 0, ',', '.') }}</li>
                        </ul>
                    </div>

                    <!-- Tampilkan reservasi hari ini -->
                    <div class="mt-4">
                        <h4 class="font-semibold">Reservasi Hari Ini:</h4>
                        @forelse($todayReservations as $reservation)
                            <p>{{ $reservation->customer->name }} - {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</p>
                        @empty
                            <p>Tidak ada reservasi untuk hari ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Waiter Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg">Selamat Datang, Waiter!</h3>

                    <div class="mt-4">
                        <h4 class="font-semibold">Meja yang Tersedia:</h4>
                        @forelse($availableTables as $table)
                            <span class="inline-block bg-green-200 text-green-800 px-2 py-1 rounded-full text-sm mr-2 mb-2">
                                Meja {{ $table->table_number }}
                            </span>
                        @empty
                            <p>Tidak ada meja yang tersedia.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        <h4 class="font-semibold">Pesanan Baru:</h4>
                        @forelse($pendingOrders as $order)
                            <p>Pesanan #{{ $order->order_id }} menunggu untuk diantar.</p>
                        @empty
                            <p>Tidak ada pesanan baru.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

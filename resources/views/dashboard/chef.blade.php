<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chef Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg">Selamat Datang, Chef!</h3>
                    <p>Berikut adalah daftar pesanan yang perlu disiapkan.</p>

                    <div class="mt-4">
                        <h4 class="font-semibold">Pesanan Masuk:</h4>
                        @forelse($pendingOrders as $order)
                            <div class="border-b py-2">
                                <p><strong>Order #{{ $order->order_id }}</strong> (Status: {{ $order->status }})</p>
                                <ul>
                                    @foreach($order->orderItems as $item)
                                        <li>{{ $item->quantity }}x {{ $item->menuItem->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p>Tidak ada pesanan yang perlu disiapkan saat ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

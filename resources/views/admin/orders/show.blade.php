<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- PERBAIKAN: Menampilkan order_number, bukan id --}}
                    <h3 class="text-2xl font-bold mb-4">Order #{{ $order->order_number }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            {{-- PERBAIKAN: Menampilkan nama meja, staf, dan customer dengan aman --}}
                            <p><strong>Table:</strong> {{ $order->table->table_number ?? 'N/A' }}</p>
                            <p><strong>Staff:</strong> {{ $order->staff->name ?? 'N/A' }}</p>
                            <p><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                            {{-- PERBAIKAN: Menggunakan created_at untuk tanggal pesanan yang konsisten --}}
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                             <p><strong>Status:</strong> <span class="px-2 py-1 text-sm font-semibold rounded-full 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($order->status == 'paid') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span></p>
                            {{-- PERBAIKAN: Menggunakan total_amount --}}
                            <p class="text-xl font-bold">Total: USD {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-xl font-semibold mb-2">Order Items</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">Item Name</th>
                                    <th class="px-6 py-3 text-center">Quantity</th>
                                    <th class="px-6 py-3 text-right">Price</th>
                                    <th class="px-6 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <tr>
                                    {{-- Pastikan relasi menuItem ada di model OrderItem --}}
                                    <td class="px-6 py-4">{{ $item->menuItem->name ?? 'Item not found' }}</td>
                                    <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right">USD {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">USD {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-6 flex justify-end">
                        <a href="{{ route('staff.orders.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">Back to Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


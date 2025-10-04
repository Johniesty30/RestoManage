<x-admin-layout>
    <x-slot name="header">
        Low Stock Alerts
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Low Stock Alerts</h1>
            <p class="text-gray-600">Items that need immediate attention</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('staff.inventory.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                Back to Inventory
            </a>
            <a href="{{ route('staff.inventory.bulk-update.show') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                📝 Bulk Update
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <span class="text-orange-600 text-2xl">⚠️</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Total Low Stock Items</p>
                    <p class="text-2xl font-bold text-orange-800">{{ $stats['total_low_stock'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <span class="text-red-600 text-2xl">❌</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Critical Items (Out of Stock)</p>
                    <p class="text-2xl font-bold text-red-800">{{ $stats['critical_items'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Items Table -->
    <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Current Stock
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Min Stock
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock Level
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lowStockItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->current_stock }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->min_stock }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->unit_of_measure }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $percentage = $item->min_stock > 0 ? ($item->current_stock / $item->min_stock) * 100 : 0;
                                $color = $percentage <= 0 ? 'red' : ($percentage <= 50 ? 'orange' : 'yellow');
                            @endphp
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ round($percentage, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('staff.inventory.adjust-stock.show', $item) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                    Restock
                                </a>
                                <a href="{{ route('staff.inventory.show', $item) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-400 text-4xl mb-2">🎉</div>
                            <h3 class="text-lg font-medium text-gray-900">No low stock items!</h3>
                            <p class="text-gray-500 mt-1">All inventory items are well stocked.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lowStockItems->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $lowStockItems->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>

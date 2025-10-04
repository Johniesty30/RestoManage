<x-admin-layout>
    <x-slot name="header">
        Inventory Management
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Stock Alert Cards -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">📦</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Items</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $inventoryItems->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">⚠️</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Low Stock</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $lowStockCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('staff.inventory.index', ['stock_status' => 'low_stock']) }}"
                       class="font-medium text-yellow-600 hover:text-yellow-500">
                        View all
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">🚫</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Out of Stock</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $outOfStockCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('staff.inventory.index', ['stock_status' => 'out_of_stock']) }}"
                       class="font-medium text-red-600 hover:text-red-500">
                        View all
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <h3 class="text-lg font-semibold text-gray-900">Inventory Items</h3>

                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                    <!-- Search Form -->
                    <form method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search items..."
                               class="border-gray-300 rounded-md shadow-sm">
                        <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Search
                        </button>
                    </form>

                    <!-- Stock Status Filter -->
                    <select name="stock_status" onchange="this.form.submit()"
                            class="border-gray-300 rounded-md shadow-sm">
                        <option value="">All Status</option>
                        <option value="in_stock" {{ $stock_status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ $stock_status == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ $stock_status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>

                    <!-- Create New Button -->
                    <a href="{{ route('staff.inventory.create') }}"
                       class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 text-center">
                        Add New Item
                    </a>
                </div>
            </div>
        </div>

        <!-- Inventory Items Table -->
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
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inventoryItems as $item)
                    <tr>
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
                                $status = $item->getStockStatus();
                                $statusColors = [
                                    'in_stock' => 'bg-green-100 text-green-800',
                                    'low_stock' => 'bg-yellow-100 text-yellow-800',
                                    'out_of_stock' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$status] }}">
                                {{ str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('staff.inventory.show', $item) }}"
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('staff.inventory.stock-update', $item) }}"
                               class="text-green-600 hover:text-green-900">Update Stock</a>
                            <a href="{{ route('staff.inventory.edit', $item) }}"
                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('staff.inventory.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No inventory items found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $inventoryItems->links() }}
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('staff.inventory.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition-colors">
                <div class="text-2xl mb-2">➕</div>
                <div class="text-sm font-medium">Add New Item</div>
            </a>

            <a href="{{ route('staff.inventory.reports') }}"
               class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition-colors">
                <div class="text-2xl mb-2">📊</div>
                <div class="text-sm font-medium">Stock Reports</div>
            </a>

            <a href="{{ route('staff.inventory.index', ['stock_status' => 'low_stock']) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-lg text-center transition-colors">
                <div class="text-2xl mb-2">⚠️</div>
                <div class="text-sm font-medium">Low Stock Alerts</div>
            </a>

            <a href="{{ route('staff.inventory.index', ['stock_status' => 'out_of_stock']) }}"
               class="bg-red-500 hover:bg-red-600 text-white p-4 rounded-lg text-center transition-colors">
                <div class="text-2xl mb-2">🚫</div>
                <div class="text-sm font-medium">Out of Stock</div>
            </a>
        </div>
    </div>
</x-admin-layout>

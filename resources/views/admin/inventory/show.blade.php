<x-admin-layout>
    <x-slot name="header">
        Inventory Item - {{ $inventoryItem->name }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Stock Status Alert -->
            @php
                $status = $inventoryItem->getStockStatus();
                $alertColors = [
                    'in_stock' => 'bg-green-50 border-green-200 text-green-800',
                    'low_stock' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                    'out_of_stock' => 'bg-red-50 border-red-200 text-red-800'
                ];
            @endphp
            <div class="mb-6 p-4 border rounded-lg {{ $alertColors[$status] }}">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($status === 'in_stock')
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($status === 'low_stock')
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">
                            Stock Status: {{ str_replace('_', ' ', ucfirst($status)) }}
                        </h3>
                        <div class="mt-1 text-sm">
                            @if($status === 'low_stock')
                                Current stock is below minimum threshold. Consider restocking.
                            @elseif($status === 'out_of_stock')
                                Item is out of stock. Immediate restocking required.
                            @else
                                Stock level is adequate.
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Item Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Item Details</h3>
                        </div>
                        <div class="p-6 bg-white">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Item Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $inventoryItem->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Unit of Measure</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $inventoryItem->unit_of_measure }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ $inventoryItem->current_stock }} {{ $inventoryItem->unit_of_measure }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Minimum Stock</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $inventoryItem->min_stock }} {{ $inventoryItem->unit_of_measure }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Stock Difference</dt>
                                    <dd class="mt-1 text-sm font-medium
                                        {{ $inventoryItem->current_stock - $inventoryItem->min_stock >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $inventoryItem->current_stock - $inventoryItem->min_stock }}
                                        {{ $inventoryItem->unit_of_measure }}
                                        {{ $inventoryItem->current_stock - $inventoryItem->min_stock >= 0 ? 'above' : 'below' }} minimum
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h4>
                            <div class="space-y-3">
                                <a href="{{ route('staff.inventory.stock-update', $inventoryItem) }}"
                                   class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-md text-center block transition-colors">
                                    Update Stock
                                </a>
                                <a href="{{ route('staff.inventory.edit', $inventoryItem) }}"
                                   class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-md text-center block transition-colors">
                                    Edit Item
                                </a>
                                <form action="{{ route('staff.inventory.destroy', $inventoryItem) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-md transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this inventory item? This action cannot be undone.')">
                                        Delete Item
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Navigation</h4>
                            <div class="space-y-2">
                                <a href="{{ route('staff.inventory.index') }}"
                                   class="text-blue-600 hover:text-blue-900 block py-2">
                                    ← Back to Inventory List
                                </a>
                                <a href="{{ route('staff.inventory.reports') }}"
                                   class="text-blue-600 hover:text-blue-900 block py-2">
                                    📊 View Stock Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

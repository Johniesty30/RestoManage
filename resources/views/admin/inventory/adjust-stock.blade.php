<x-admin-layout>
    <x-slot name="header">
        Adjust Stock: {{ $inventoryItem->name }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('staff.inventory.show', $inventoryItem) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
            ← Back to Item Details
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Current Stock Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Stock Information</h3>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Item Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inventoryItem->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Current Stock</label>
                    <p class="text-2xl font-bold text-blue-600">{{ $inventoryItem->current_stock }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Minimum Stock</label>
                    <p class="text-lg text-gray-900">{{ $inventoryItem->min_stock }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Unit of Measure</label>
                    <p class="text-lg text-gray-900">{{ $inventoryItem->unit_of_measure }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Stock Status</label>
                    @php
                        $status = $inventoryItem->getStockStatus();
                        if (is_string($status)) {
                            // If it returns a string, determine status based on stock levels
                            if ($inventoryItem->current_stock <= 0) {
                                $statusArray = ['status' => 'out_of_stock', 'text' => 'Out of Stock'];
                            } elseif ($inventoryItem->current_stock <= $inventoryItem->min_stock) {
                                $statusArray = ['status' => 'low_stock', 'text' => 'Low Stock'];
                            } else {
                                $statusArray = ['status' => 'in_stock', 'text' => 'In Stock'];
                            }
                        } else {
                            $statusArray = $status;
                        }
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                        @if($statusArray['status'] == 'in_stock') bg-green-100 text-green-800
                        @elseif($statusArray['status'] == 'low_stock') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ $statusArray['text'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Adjust Stock Form -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <form action="{{ route('staff.inventory.adjust-stock', $inventoryItem) }}" method="POST">
                    @csrf

                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Adjust Stock Level</h3>

                        <!-- Adjustment Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Adjustment Type</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="adjustment_type" value="add" class="sr-only" checked>
                                    <div class="flex items-center justify-center w-full p-4 border-2 border-gray-200 rounded-lg hover:border-green-500">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2">➕</div>
                                            <div class="font-medium">Add Stock</div>
                                            <div class="text-sm text-gray-500">Increase quantity</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="adjustment_type" value="subtract" class="sr-only">
                                    <div class="flex items-center justify-center w-full p-4 border-2 border-gray-200 rounded-lg hover:border-red-500">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2">➖</div>
                                            <div class="font-medium">Subtract Stock</div>
                                            <div class="text-sm text-gray-500">Decrease quantity</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="adjustment_type" value="set" class="sr-only">
                                    <div class="flex items-center justify-center w-full p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2">🎯</div>
                                            <div class="font-medium">Set Exact Value</div>
                                            <div class="text-sm text-gray-500">Set specific quantity</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-6">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                            <input type="number" name="quantity" id="quantity" value="0" step="0.01" min="0" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="mb-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Adjustment *</label>
                            <select name="reason" id="reason" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a reason...</option>
                                <option value="Restock">Restock from supplier</option>
                                <option value="Usage">Daily usage</option>
                                <option value="Waste">Spoilage/waste</option>
                                <option value="Correction">Inventory correction</option>
                                <option value="Theft/Loss">Theft or loss</option>
                                <option value="Other">Other reason</option>
                            </select>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Additional details about this adjustment..."></textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('staff.inventory.show', $inventoryItem) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md mr-3">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            Update Stock
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <form action="{{ route('staff.inventory.adjust-stock', $inventoryItem) }}" method="POST" class="bg-green-50 border border-green-200 rounded-lg p-4">
                    @csrf
                    <input type="hidden" name="adjustment_type" value="set">
                    <input type="hidden" name="quantity" value="{{ $inventoryItem->min_stock }}">
                    <input type="hidden" name="reason" value="Correction">
                    <input type="hidden" name="notes" value="Quick set to minimum stock level">
                    <button type="submit" class="w-full text-left">
                        <div class="flex items-center">
                            <div class="text-2xl text-green-600 mr-3">🎯</div>
                            <div>
                                <div class="font-medium text-green-800">Set to Minimum Stock</div>
                                <div class="text-sm text-green-600">Set stock to {{ $inventoryItem->min_stock }}</div>
                            </div>
                        </div>
                    </button>
                </form>

                <form action="{{ route('staff.inventory.adjust-stock', $inventoryItem) }}" method="POST" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    @csrf
                    <input type="hidden" name="adjustment_type" value="add">
                    <input type="hidden" name="quantity" value="10">
                    <input type="hidden" name="reason" value="Restock">
                    <input type="hidden" name="notes" value="Quick restock of 10 units">
                    <button type="submit" class="w-full text-left">
                        <div class="flex items-center">
                            <div class="text-2xl text-blue-600 mr-3">📦</div>
                            <div>
                                <div class="font-medium text-blue-800">Quick Restock</div>
                                <div class="text-sm text-blue-600">Add 10 units to stock</div>
                            </div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
</x-admin-layout>

<x-admin-layout>
    <x-slot name="header">
        Update Stock - {{ $inventoryItem->name }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Current Stock Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Stock Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $inventoryItem->current_stock }}</div>
                            <div class="text-sm text-gray-600">Current Stock</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $inventoryItem->min_stock }}</div>
                            <div class="text-sm text-gray-600">Minimum Stock</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm font-medium">
                                @php
                                    $status = $inventoryItem->getStockStatus();
                                    $statusColors = [
                                        'in_stock' => 'bg-green-100 text-green-800',
                                        'low_stock' => 'bg-yellow-100 text-yellow-800',
                                        'out_of_stock' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$status] }}">
                                    {{ str_replace('_', ' ', ucfirst($status)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Status</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Update Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('staff.inventory.update-stock', $inventoryItem) }}">
                        @csrf

                        <!-- Action Type -->
                        <div class="mb-6">
                            <label for="action" class="block text-sm font-medium text-gray-700 mb-2">
                                Stock Action *
                            </label>
                            <select id="action" name="action" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Action</option>
                                <option value="add">Add Stock</option>
                                <option value="reduce">Reduce Stock</option>
                                <option value="set">Set Stock to Specific Value</option>
                            </select>
                            @error('action')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="mb-6">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity *
                            </label>
                            <input type="number" id="quantity" name="quantity" step="0.01" min="0.01" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter quantity">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Add any notes about this stock update..."></textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('staff.inventory.show', $inventoryItem) }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors">
                                Update Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 flex space-x-4">
                <a href="{{ route('staff.inventory.show', $inventoryItem) }}"
                   class="text-blue-600 hover:text-blue-900 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Item Details
                </a>
                <a href="{{ route('staff.inventory.edit', $inventoryItem) }}"
                   class="text-green-600 hover:text-green-900 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Item
                </a>
            </div>
        </div>
    </div>

    <script>
        // Dynamic placeholder based on action
        document.getElementById('action').addEventListener('change', function() {
            const quantityInput = document.getElementById('quantity');
            const action = this.value;

            switch(action) {
                case 'add':
                    quantityInput.placeholder = 'Enter quantity to add';
                    break;
                case 'reduce':
                    quantityInput.placeholder = 'Enter quantity to reduce';
                    break;
                case 'set':
                    quantityInput.placeholder = 'Enter new stock quantity';
                    break;
                default:
                    quantityInput.placeholder = 'Enter quantity';
            }
        });
    </script>
</x-admin-layout>

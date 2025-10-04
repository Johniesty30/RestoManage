<x-admin-layout>
    <x-slot name="header">
        Edit Inventory Item - {{ $inventoryItem->name }}
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('staff.inventory.update', $inventoryItem) }}">
                        @csrf
                        @method('PUT')

                        <!-- Current Stock Info -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Current Values</h4>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Stock:</span>
                                    <span class="font-medium ml-1">{{ $inventoryItem->current_stock }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Min Stock:</span>
                                    <span class="font-medium ml-1">{{ $inventoryItem->min_stock }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Unit:</span>
                                    <span class="font-medium ml-1">{{ $inventoryItem->unit_of_measure }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Item Name *
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $inventoryItem->name) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter item name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Stock -->
                        <div class="mb-6">
                            <label for="current_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                Current Stock *
                            </label>
                            <input type="number" id="current_stock" name="current_stock"
                                   value="{{ old('current_stock', $inventoryItem->current_stock) }}"
                                   step="0.01" min="0" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter current stock">
                            @error('current_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Minimum Stock -->
                        <div class="mb-6">
                            <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                Minimum Stock Level *
                            </label>
                            <input type="number" id="min_stock" name="min_stock"
                                   value="{{ old('min_stock', $inventoryItem->min_stock) }}"
                                   step="0.01" min="0" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter minimum stock level">
                            @error('min_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit of Measure -->
                        <div class="mb-6">
                            <label for="unit_of_measure" class="block text-sm font-medium text-gray-700 mb-2">
                                Unit of Measure *
                            </label>
                            <input type="text" id="unit_of_measure" name="unit_of_measure"
                                   value="{{ old('unit_of_measure', $inventoryItem->unit_of_measure) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., kg, pieces, liters">
                            @error('unit_of_measure')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="space-x-4">
                                <a href="{{ route('staff.inventory.show', $inventoryItem) }}"
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                                    Cancel
                                </a>
                                <a href="{{ route('staff.inventory.stock-update', $inventoryItem) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors">
                                    Update Stock Instead
                                </a>
                            </div>
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md transition-colors">
                                Update Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

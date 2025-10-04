<x-admin-layout>
    <x-slot name="header">
        Add New Inventory Item
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('staff.inventory.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Item Name *
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter item name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Stock -->
                        <div class="mb-6">
                            <label for="current_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                Initial Stock *
                            </label>
                            <input type="number" id="current_stock" name="current_stock"
                                   value="{{ old('current_stock', 0) }}"
                                   step="0.01" min="0" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter initial stock quantity">
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
                                   value="{{ old('min_stock', 0) }}"
                                   step="0.01" min="0" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter minimum stock level for alerts">
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
                                   value="{{ old('unit_of_measure') }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., kg, pieces, liters, boxes">
                            @error('unit_of_measure')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('staff.inventory.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors">
                                Create Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

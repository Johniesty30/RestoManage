<x-admin-layout>
    <x-slot name="header">
        Menu Items Management
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Menu Items</h1>
            <p class="text-gray-600">Manage your restaurant menu items</p>
        </div>
        <a href="{{ route('staff.menu-items.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <span class="mr-2">➕</span> Add New Item
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4">
            <form method="GET" action="{{ route('staff.menu-items.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Search by name or description...">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Filter by Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mr-2">
                            Filter
                        </button>
                        <a href="{{ route('staff.menu-items.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Menu Items Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($menuItems as $menuItem)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Image -->
            <div class="h-48 bg-gray-200 relative">
                @if($menuItem->image)
                    <img src="{{ Storage::url($menuItem->image) }}" alt="{{ $menuItem->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                        <span class="text-4xl text-gray-400">🍽️</span>
                    </div>
                @endif
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $menuItem->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $menuItem->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $menuItem->name }}</h3>
                    <span class="text-lg font-bold text-blue-600">${{ number_format($menuItem->price, 2) }}</span>
                </div>

                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    {{ $menuItem->description ?? 'No description' }}
                </p>

                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $menuItem->category->name }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $menuItem->created_at->diffForHumans() }}
                    </span>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <a href="{{ route('staff.menu-items.show', $menuItem) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                            View
                        </a>
                        <a href="{{ route('staff.menu-items.edit', $menuItem) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            Edit
                        </a>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('staff.menu-items.toggle-availability', $menuItem) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-{{ $menuItem->is_available ? 'orange' : 'green' }}-600 hover:text-{{ $menuItem->is_available ? 'orange' : 'green' }}-900 text-sm">
                                {{ $menuItem->is_available ? 'Make Unavailable' : 'Make Available' }}
                            </button>
                        </form>
                        <form action="{{ route('staff.menu-items.destroy', $menuItem) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this menu item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">🍕</div>
            <h3 class="text-lg font-medium text-gray-900">No menu items found</h3>
            <p class="text-gray-500 mt-2">Get started by creating your first menu item.</p>
            <a href="{{ route('staff.menu-items.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                Add Your First Menu Item
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($menuItems->hasPages())
    <div class="mt-6 bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $menuItems->links() }}
    </div>
    @endif

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

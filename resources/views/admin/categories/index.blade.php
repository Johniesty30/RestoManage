<x-admin-layout>
    <x-slot name="header">
        Category Management
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Menu Categories</h1>
            <p class="text-gray-600">Manage your menu categories</p>
        </div>
        <a href="{{ route('staff.categories.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <span class="mr-2">➕</span> Add New Category
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4">
            <form method="GET" action="{{ route('staff.categories.index') }}">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Search categories...">
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        Search
                    </button>
                    <a href="{{ route('staff.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                        {{ $category->menu_items_count }} items
                    </span>
                </div>

                <p class="text-gray-600 mb-4">
                    {{ $category->description ?? 'No description' }}
                </p>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        Created {{ $category->created_at->diffForHumans() }}
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('staff.categories.show', $category) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                            View
                        </a>
                        <a href="{{ route('staff.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            Edit
                        </a>
                        <form action="{{ route('staff.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
        <div class="col-span-3 text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">🍽️</div>
            <h3 class="text-lg font-medium text-gray-900">No categories found</h3>
            <p class="text-gray-500 mt-2">Get started by creating your first menu category.</p>
            <a href="{{ route('staff.categories.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                Add Your First Category
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="mt-6 bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $categories->links() }}
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

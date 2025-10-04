<x-admin-layout>
    <x-slot name="header">
        Table Management
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">🪑</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Tables</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tableStats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">✅</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Available</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tableStats['available'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">👥</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Occupied</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tableStats['occupied'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">📅</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Reserved</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tableStats['reserved'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <h3 class="text-lg font-semibold text-gray-900">Restaurant Tables</h3>

                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                    <!-- Search Form -->
                    <form method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search tables..."
                               class="border-gray-300 rounded-md shadow-sm">
                        <select name="status" onchange="this.form.submit()"
                                class="border-gray-300 rounded-md shadow-sm">
                            <option value="">All Status</option>
                            @foreach($statuses as $statusOption)
                                <option value="{{ $statusOption }}" {{ $status == $statusOption ? 'selected' : '' }}>
                                    {{ ucfirst($statusOption) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Search
                        </button>
                    </form>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <a href="{{ route('staff.tables.create') }}"
                           class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                            Add New Table
                        </a>
                        <a href="{{ route('staff.tables.availability-checker') }}"
                           class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600">
                            Check Availability
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($tables as $table)
                <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <!-- Table Header -->
                    <div class="px-4 py-3 border-b {{
                        $table->status === 'available' ? 'bg-green-50 border-green-200' :
                        ($table->status === 'occupied' ? 'bg-orange-50 border-orange-200' :
                        ($table->status === 'reserved' ? 'bg-purple-50 border-purple-200' : 'bg-gray-50 border-gray-200'))
                    }}">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-lg">Table {{ $table->table_number }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $table->status === 'available' ? 'bg-green-100 text-green-800' :
                                   ($table->status === 'occupied' ? 'bg-orange-100 text-orange-800' :
                                   ($table->status === 'reserved' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))
                                }}">
                                {{ ucfirst($table->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Table Details -->
                    <div class="p-4">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Capacity:</span>
                                <span class="text-sm font-medium">{{ $table->capacity }} people</span>
                            </div>
                            @if($table->location)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Location:</span>
                                <span class="text-sm font-medium">{{ $table->location }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Upcoming Reservations:</span>
                                <span class="text-sm font-medium">{{ $table->reservations_count }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('staff.tables.show', $table) }}"
                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-md text-sm">
                                View
                            </a>
                            <a href="{{ route('staff.tables.edit', $table) }}"
                               class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded-md text-sm">
                                Edit
                            </a>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-2 flex space-x-2">
                            @if($table->status === 'available' || $table->status === 'maintenance')
                                <form action="{{ route('staff.tables.toggle-status', $table) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-md text-sm">
                                        {{ $table->status === 'available' ? 'Maintenance' : 'Available' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8">
                    <div class="text-gray-500">No tables found.</div>
                    <a href="{{ route('staff.tables.create') }}"
                       class="mt-2 inline-block bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Add Your First Table
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($tables->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tables->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>

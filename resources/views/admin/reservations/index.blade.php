<x-admin-layout>
    <x-slot name="header">
        Reservation Management
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">📅</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Reservations</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $reservationStats['today'] }}</dd>
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
                            <span class="text-white text-sm">⏰</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Upcoming</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $reservationStats['upcoming'] }}</dd>
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
                            <span class="text-white text-sm">⏳</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $reservationStats['pending'] }}</dd>
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
                            <span class="text-white text-sm">🗓️</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Calendar View</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <a href="{{ route('staff.reservations.calendar') }}" class="text-purple-600 hover:text-purple-500">View</a>
                            </dd>
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
                <h3 class="text-lg font-semibold text-gray-900">Reservations</h3>

                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                    <!-- Search Form -->
                    <form method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search customer..."
                               class="border-gray-300 rounded-md shadow-sm">
                        <input type="date" name="date" value="{{ $date }}"
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
                        <a href="{{ route('staff.reservations.create') }}"
                           class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                            New Reservation
                        </a>
                        <a href="{{ route('staff.reservations.calendar') }}"
                           class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600">
                            Calendar View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservations Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Table & Guests
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reservation Time
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
                    @forelse($reservations as $reservation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $reservation->customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->customer->email }}</div>
                                    @if($reservation->customer->phone)
                                    <div class="text-sm text-gray-500">{{ $reservation->customer->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Table {{ $reservation->table->table_number }}</div>
                            <div class="text-sm text-gray-500">{{ $reservation->guests }} guests</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $reservation->reservation_time->format('M j, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $reservation->reservation_time->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                   ($reservation->status === 'seated' ? 'bg-blue-100 text-blue-800' :
                                   ($reservation->status === 'completed' ? 'bg-gray-100 text-gray-800' :
                                   ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')))
                                }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('staff.reservations.show', $reservation) }}"
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('staff.reservations.edit', $reservation) }}"
                               class="text-green-600 hover:text-green-900">Edit</a>

                            @if($reservation->status === 'confirmed')
                                <form action="{{ route('staff.reservations.mark-seated', $reservation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-orange-600 hover:text-orange-900">Mark Seated</button>
                                </form>
                            @endif

                            @if($reservation->status === 'seated')
                                <form action="{{ route('staff.reservations.mark-completed', $reservation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-gray-600 hover:text-gray-900">Complete</button>
                                </form>
                            @endif

                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                                <form action="{{ route('staff.reservations.cancel', $reservation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No reservations found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reservations->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>

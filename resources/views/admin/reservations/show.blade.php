<x-admin-layout>
    <x-slot name="header">
        Reservation Details - #{{ $reservation->id }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Reservation Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Reservation Information</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                               ($reservation->status === 'seated' ? 'bg-blue-100 text-blue-800' :
                               ($reservation->status === 'completed' ? 'bg-gray-100 text-gray-800' :
                               ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')))
                            }}">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Information -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-3">CUSTOMER INFORMATION</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-gray-600">Name:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $reservation->customer->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Email:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $reservation->customer->email }}</span>
                                </div>
                                @if($reservation->customer->phone)
                                <div>
                                    <span class="text-sm text-gray-600">Phone:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $reservation->customer->phone }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Reservation Details -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-3">RESERVATION DETAILS</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-gray-600">Table:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">
                                        Table {{ $reservation->table->table_number }}
                                        (Capacity: {{ $reservation->table->capacity }})
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Guests:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $reservation->guests }}</span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Date & Time:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">
                                        {{ $reservation->reservation_time->format('M j, Y g:i A') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Duration:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">2 hours</span>
                                </div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        @if($reservation->special_requests)
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">SPECIAL REQUESTS</h4>
                            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $reservation->special_requests }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('staff.reservations.edit', $reservation) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors">
                            Edit Reservation
                        </a>

                        @if($reservation->status === 'confirmed')
                            <form action="{{ route('staff.reservations.mark-seated', $reservation) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md transition-colors">
                                    Mark as Seated
                                </button>
                            </form>
                        @endif

                        @if($reservation->status === 'seated')
                            <form action="{{ route('staff.reservations.mark-completed', $reservation) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif

                        @if(in_array($reservation->status, ['pending', 'confirmed']))
                            <form action="{{ route('staff.reservations.cancel', $reservation) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition-colors"
                                        onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                    Cancel Reservation
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('staff.reservations.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                            Back to Reservations
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Table Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">TABLE DETAILS</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-gray-600">Table Number:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $reservation->table->table_number }}</span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Capacity:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $reservation->table->capacity }} people</span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $reservation->table->status === 'available' ? 'bg-green-100 text-green-800' :
                                           ($reservation->table->status === 'occupied' ? 'bg-orange-100 text-orange-800' :
                                           ($reservation->table->status === 'reserved' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))
                                        }}">
                                        {{ ucfirst($reservation->table->status) }}
                                    </span>
                                </div>
                                @if($reservation->table->location)
                                <div>
                                    <span class="text-sm text-gray-600">Location:</span>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $reservation->table->location }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">ACTIONS</h4>
                            <div class="space-y-2">
                                <a href="{{ route('staff.tables.show', $reservation->table) }}"
                                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-md transition-colors">
                                    View Table Details
                                </a>
                                <a href="{{ route('staff.tables.availability-checker') }}"
                                   class="block w-full bg-purple-500 hover:bg-purple-600 text-white text-center py-2 rounded-md transition-colors">
                                    Check Table Availability
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

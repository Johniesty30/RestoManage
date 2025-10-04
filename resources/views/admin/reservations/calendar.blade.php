<x-admin-layout>
    <x-slot name="header">
        Reservation Calendar - {{ $selectedDate->format('F j, Y') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Calendar Navigation -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Reservation Calendar</h3>

                        <div class="flex items-center space-x-4">
                            <!-- Date Navigation -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('staff.reservations.calendar', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d')]) }}"
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-md">
                                    ←
                                </a>

                                <form method="GET" class="flex items-center space-x-2">
                                    <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}"
                                           class="border-gray-300 rounded-md shadow-sm">
                                    <button type="submit"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600">
                                        Go
                                    </button>
                                </form>

                                <a href="{{ route('staff.reservations.calendar', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d')]) }}"
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-md">
                                    →
                                </a>

                                <a href="{{ route('staff.reservations.calendar', ['date' => today()->format('Y-m-d')]) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">
                                    Today
                                </a>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('staff.reservations.create') }}"
                                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                                    New Reservation
                                </a>
                                <a href="{{ route('staff.reservations.index') }}"
                                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                                    List View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Slots -->
            <div class="bg-white shadow rounded-lg">
                <div class="overflow-hidden">
                    @php
                        $timeSlots = [];
                        for ($hour = 10; $hour <= 22; $hour++) {
                            $timeSlots[] = sprintf('%02d:00', $hour);
                        }
                    @endphp

                    @foreach($timeSlots as $timeSlot)
                        <div class="border-b border-gray-200">
                            <div class="flex">
                                <!-- Time Header -->
                                <div class="w-24 flex-shrink-0 bg-gray-50 p-4 border-r border-gray-200">
                                    <div class="text-sm font-medium text-gray-900">{{ $timeSlot }}</div>
                                </div>

                                <!-- Reservations for this time slot -->
                                <div class="flex-1 p-4 min-h-20">
                                    @if(isset($reservations[$timeSlot]))
                                        <div class="space-y-2">
                                            @foreach($reservations[$timeSlot] as $reservation)
                                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 hover:bg-blue-100 transition-colors">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-3">
                                                                <div class="flex-shrink-0">
                                                                    <div class="w-3 h-3 rounded-full
                                                                        {{ $reservation->status === 'confirmed' ? 'bg-green-500' :
                                                                           ($reservation->status === 'seated' ? 'bg-blue-500' :
                                                                           ($reservation->status === 'completed' ? 'bg-gray-500' : 'bg-yellow-500'))
                                                                        }}">
                                                                    </div>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                                        {{ $reservation->customer->name }}
                                                                    </p>
                                                                    <p class="text-sm text-gray-500 truncate">
                                                                        Table {{ $reservation->table->table_number }} •
                                                                        {{ $reservation->guests }} guests •
                                                                        {{ $reservation->reservation_time->format('g:i A') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                                                   ($reservation->status === 'seated' ? 'bg-blue-100 text-blue-800' :
                                                                   ($reservation->status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800'))
                                                                }}">
                                                                {{ ucfirst($reservation->status) }}
                                                            </span>
                                                            <a href="{{ route('staff.reservations.show', $reservation) }}"
                                                               class="text-blue-600 hover:text-blue-900 text-sm">
                                                                View
                                                            </a>
                                                        </div>
                                                    </div>

                                                    @if($reservation->special_requests)
                                                        <div class="mt-2 text-xs text-gray-600 bg-white p-2 rounded border">
                                                            <strong>Special requests:</strong> {{ $reservation->special_requests }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-gray-400 text-sm py-4">
                                            No reservations
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-6 bg-white shadow rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Status Legend</h4>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-sm text-gray-600">Confirmed</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <span class="text-sm text-gray-600">Seated</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                        <span class="text-sm text-gray-600">Completed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

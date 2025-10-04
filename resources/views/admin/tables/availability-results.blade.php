<x-admin-layout>
    <x-slot name="header">
        Availability Results
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Criteria -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Search Criteria</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Date:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ $reservationTime->format('M j, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Time:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ $reservationTime->format('g:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Guests:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ $request->guests }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Duration:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ $request->duration }} hours</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Available Tables</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $availableTables->count() }} tables found
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($availableTables->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($availableTables as $table)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-lg">Table {{ $table->table_number }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Available
                                    </span>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Capacity:</span>
                                        <span class="font-medium">{{ $table->capacity }} people</span>
                                    </div>
                                    @if($table->location)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Location:</span>
                                        <span class="font-medium">{{ $table->location }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('staff.reservations.create', ['table_id' => $table->id, 'reservation_time' => $reservationTime->format('Y-m-d\TH:i'), 'guests' => $request->guests]) }}"
                                       class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-md text-sm">
                                        Book Now
                                    </a>
                                    <a href="{{ route('staff.tables.show', $table) }}"
                                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white text-center py-2 rounded-md text-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg mb-4">No available tables found for your criteria.</div>
                            <p class="text-gray-600 mb-4">Please try adjusting your search criteria.</p>
                            <a href="{{ route('staff.tables.availability-checker') }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors">
                                New Search
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('staff.tables.availability-checker') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                    New Search
                </a>
                <a href="{{ route('staff.tables.index') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors">
                    Back to Tables
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>

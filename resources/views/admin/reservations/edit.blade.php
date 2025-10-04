  <x-admin-layout>
    <x-slot name="header">
        Edit Reservation - #{{ $reservation->id }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('staff.reservations.update', $reservation) }}" id="reservationForm">
                        @csrf
                        @method('PUT')

                        <!-- Current Status -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Current Status</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                   ($reservation->status === 'seated' ? 'bg-blue-100 text-blue-800' :
                                   ($reservation->status === 'completed' ? 'bg-gray-100 text-gray-800' :
                                   ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')))
                                }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>

                        <!-- Customer Selection -->
                        <div class="mb-6">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Customer *
                            </label>
                            <select id="customer_id" name="customer_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $reservation->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Table Selection -->
                        <div class="mb-6">
                            <label for="table_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Table *
                            </label>
                            <select id="table_id" name="table_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Table</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}"
                                            data-capacity="{{ $table->capacity }}"
                                            {{ old('table_id', $reservation->table_id) == $table->id ? 'selected' : '' }}>
                                        Table {{ $table->table_number }} (Capacity: {{ $table->capacity }})
                                        @if($table->location) - {{ $table->location }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('table_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reservation Time -->
                        <div class="mb-6">
                            <label for="reservation_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Reservation Date & Time *
                            </label>
                            <input type="datetime-local" id="reservation_time" name="reservation_time"
                                   value="{{ old('reservation_time', $reservation->reservation_time->format('Y-m-d\TH:i')) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('reservation_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Number of Guests -->
                        <div class="mb-6">
                            <label for="guests" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Guests *
                            </label>
                            <input type="number" id="guests" name="guests" value="{{ old('guests', $reservation->guests) }}"
                                   min="1" max="20" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('guests')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p id="capacity-warning" class="mt-1 text-sm text-red-600 hidden">
                                Selected table capacity exceeded!
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status *
                            </label>
                            <select id="status" name="status" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="seated" {{ old('status', $reservation->status) == 'seated' ? 'selected' : '' }}>Seated</option>
                                <option value="completed" {{ old('status', $reservation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Requests -->
                        <div class="mb-6">
                            <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Requests
                            </label>
                            <textarea id="special_requests" name="special_requests" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Any special requirements or requests...">{{ old('special_requests', $reservation->special_requests) }}</textarea>
                            @error('special_requests')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="space-x-2">
                                <a href="{{ route('staff.reservations.show', $reservation) }}"
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                                    Cancel
                                </a>
                                <a href="{{ route('staff.reservations.index') }}"
                                   class="bg-blue-300 hover:bg-blue-400 text-blue-800 px-4 py-2 rounded-md transition-colors">
                                    Back to List
                                </a>
                            </div>
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md transition-colors">
                                Update Reservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableSelect = document.getElementById('table_id');
            const guestsInput = document.getElementById('guests');
            const capacityWarning = document.getElementById('capacity-warning');

            // Check capacity when table or guests change
            function checkCapacity() {
                const selectedTable = tableSelect.options[tableSelect.selectedIndex];
                const tableCapacity = selectedTable ? parseInt(selectedTable.getAttribute('data-capacity')) : 0;
                const guests = parseInt(guestsInput.value);

                if (tableCapacity > 0 && guests > tableCapacity) {
                    capacityWarning.classList.remove('hidden');
                } else {
                    capacityWarning.classList.add('hidden');
                }
            }

            tableSelect.addEventListener('change', checkCapacity);
            guestsInput.addEventListener('input', checkCapacity);

            // Initial capacity check
            checkCapacity();
        });
    </script>
</x-admin-layout>

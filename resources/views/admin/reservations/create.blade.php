<x-admin-layout>
    <x-slot name="header">
        Create New Reservation
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('staff.reservations.store') }}" id="reservationForm">
                        @csrf

                        <!-- Customer Selection -->
                        <div class="mb-6">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Customer *
                            </label>
                            <select id="customer_id" name="customer_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                                            {{ old('table_id') == $table->id ? 'selected' : '' }}>
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
                                   value="{{ old('reservation_time') }}" required
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
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
                            <input type="number" id="guests" name="guests" value="{{ old('guests', 2) }}"
                                   min="1" max="20" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('guests')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p id="capacity-warning" class="mt-1 text-sm text-red-600 hidden">
                                Selected table capacity exceeded!
                            </p>
                        </div>

                        <!-- Special Requests -->
                        <div class="mb-6">
                            <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Requests
                            </label>
                            <textarea id="special_requests" name="special_requests" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Any special requirements or requests...">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Auto Assign Button -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">Not sure which table to choose?</h4>
                                    <p class="text-sm text-blue-600">Let us find the best available table for you.</p>
                                </div>
                                <button type="button" id="autoAssignBtn"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Auto Assign Table
                                </button>
                            </div>
                            <div id="autoAssignResult" class="mt-2 text-sm text-green-600 hidden"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('staff.reservations.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors">
                                Create Reservation
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
            const autoAssignBtn = document.getElementById('autoAssignBtn');
            const autoAssignResult = document.getElementById('autoAssignResult');
            const reservationTimeInput = document.getElementById('reservation_time');
            const reservationForm = document.getElementById('reservationForm');

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

            // Auto assign table
            autoAssignBtn.addEventListener('click', function() {
                const reservationTime = reservationTimeInput.value;
                const guests = guestsInput.value;

                if (!reservationTime || !guests) {
                    alert('Please select reservation time and number of guests first.');
                    return;
                }

                // Show loading
                autoAssignBtn.disabled = true;
                autoAssignBtn.textContent = 'Finding table...';

                fetch('{{ route("staff.reservations.auto-assign") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        reservation_time: reservationTime,
                        guests: parseInt(guests)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Find and select the table in dropdown
                        const tableOption = Array.from(tableSelect.options).find(option =>
                            option.value == data.table.id
                        );
                        if (tableOption) {
                            tableSelect.value = data.table.id;
                            autoAssignResult.textContent = data.message;
                            autoAssignResult.classList.remove('hidden');
                            checkCapacity();
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error finding available table.');
                })
                .finally(() => {
                    autoAssignBtn.disabled = false;
                    autoAssignBtn.textContent = 'Auto Assign Table';
                });
            });

            // Initial capacity check
            checkCapacity();
        });
    </script>
</x-admin-layout>

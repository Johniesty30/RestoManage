<x-admin-layout>
    <x-slot name="header">
        Table Availability Checker
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('staff.tables.check-availability') }}">
                        @csrf

                        <!-- Date -->
                        <div class="mb-6">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date *
                            </label>
                            <input type="date" id="date" name="date" value="{{ old('date', today()->format('Y-m-d')) }}" required
                                   min="{{ today()->format('Y-m-d') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time -->
                        <div class="mb-6">
                            <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                                Time *
                            </label>
                            <input type="time" id="time" name="time" value="{{ old('time', '19:00') }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guests -->
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
                        </div>

                        <!-- Duration -->
                        <div class="mb-6">
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                Duration (hours) *
                            </label>
                            <select id="duration" name="duration" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ old('duration', 2) == 1 ? 'selected' : '' }}>1 hour</option>
                                <option value="2" {{ old('duration', 2) == 2 ? 'selected' : '' }}>2 hours</option>
                                <option value="3" {{ old('duration', 2) == 3 ? 'selected' : '' }}>3 hours</option>
                                <option value="4" {{ old('duration', 2) == 4 ? 'selected' : '' }}>4 hours</option>
                            </select>
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('staff.tables.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                                Back to Tables
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors">
                                Check Availability
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

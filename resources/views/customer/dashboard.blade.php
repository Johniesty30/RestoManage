<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customer Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg">Welcome back, <strong>{{ auth()->user()->name }}</strong>!</p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Loyalty Points -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800">Loyalty Points</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ auth()->user()->loyalty_points }}</p>
                        </div>

                        <!-- Order History -->
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-800">Total Orders</h3>
                            <p class="text-2xl font-bold text-green-600">0</p>
                        </div>

                        <!-- Reservations -->
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-yellow-800">Upcoming Reservations</h3>
                            <p class="text-2xl font-bold text-yellow-600">0</p>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Make Reservation
                            </a>
                            <a href="#" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                View Order History
                            </a>
                            <a href="#" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                Update Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

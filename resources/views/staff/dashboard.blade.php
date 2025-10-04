<x-staff-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Staff Dashboard - {{ ucfirst(auth()->user()->role) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg">Welcome, <strong>{{ auth()->user()->name }}</strong>!</p>
                    <p class="mt-2">You are logged in as <strong>{{ auth()->user()->role }}</strong>.</p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Quick Stats -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800">Today's Orders</h3>
                            <p class="text-2xl font-bold text-blue-600">0</p>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-800">Reservations</h3>
                            <p class="text-2xl font-bold text-green-600">0</p>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-yellow-800">Tasks</h3>
                            <p class="text-2xl font-bold text-yellow-600">0</p>
                        </div>
                    </div>

                    <!-- Role-specific content -->
                    @if(auth()->user()->isAdmin())
                        <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                            <h3 class="font-semibold text-purple-800">Admin Tools</h3>
                            <p class="text-purple-600">Access the admin panel for full system control.</p>
                            <a href="{{ route('staff.admin.dashboard') }}" class="mt-2 inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                Go to Admin Panel
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->isChef())
                        <div class="mt-6 p-4 bg-orange-50 rounded-lg">
                            <h3 class="font-semibold text-orange-800">Kitchen Operations</h3>
                            <p class="text-orange-600">Manage food preparation and orders.</p>
                        </div>
                    @endif

                    @if(auth()->user()->isWaiter())
                        <div class="mt-6 p-4 bg-teal-50 rounded-lg">
                            <h3 class="font-semibold text-teal-800">Service Management</h3>
                            <p class="text-teal-600">Take orders and manage tables.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-staff-layout>

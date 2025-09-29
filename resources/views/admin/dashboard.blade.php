<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg">Welcome to Admin Panel, <strong>{{ auth()->user()->name }}</strong>!</p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Admin Stats -->
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-red-800">Total Users</h3>
                            <p class="text-2xl font-bold text-red-600">0</p>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800">Total Orders</h3>
                            <p class="text-2xl font-bold text-blue-600">0</p>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-800">Revenue</h3>
                            <p class="text-2xl font-bold text-green-600">$0</p>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-purple-800">Menu Items</h3>
                            <p class="text-2xl font-bold text-purple-600">0</p>
                        </div>
                    </div>

                    <!-- Admin Tools -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Admin Tools</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <a href="#" class="block p-4 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <h4 class="font-semibold">User Management</h4>
                                <p class="text-sm text-gray-600">Manage all users and roles</p>
                            </a>

                            <a href="#" class="block p-4 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <h4 class="font-semibold">Menu Management</h4>
                                <p class="text-sm text-gray-600">Manage menu items and categories</p>
                            </a>

                            <a href="#" class="block p-4 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <h4 class="font-semibold">System Settings</h4>
                                <p class="text-sm text-gray-600">Configure system preferences</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<x-app-layout>

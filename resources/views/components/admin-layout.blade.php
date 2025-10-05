<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-800 text-white">
                <div class="p-4">
                    <h1 class="text-xl font-bold">🍽️ {{ config('app.name', 'Restaurant') }}</h1>
                    <p class="text-gray-400 text-sm">Admin Panel</p>
                </div>

                <nav class="mt-6">
                    <div class="px-4 space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('staff.dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                            📊 Dashboard
                        </a>

                        <!-- User Management -->
                        <a href="{{ route('staff.users.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.users.*') ? 'bg-gray-700 text-white' : '' }}">
                            👥 Users
                        </a>

                        <!-- Menu Management -->
                        <div class="px-4 py-2 text-gray-400 text-sm font-medium">Menu Management</div>
                        <a href="{{ route('staff.categories.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.categories.*') ? 'bg-gray-700 text-white' : '' }}">
                            📁 Categories
                        </a>
                        <a href="{{ route('staff.menu-items.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.menu-items.*') ? 'bg-gray-700 text-white' : '' }}">
                            📋 Menu Items
                        </a>

                        <!-- Inventory -->
                        <a href="{{ route('staff.inventory.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.inventory.*') ? 'bg-gray-700 text-white' : '' }}">
                            📦 Inventory
                        </a>

                        <!-- Tables -->
                        <a href="{{ route('staff.tables.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.tables.*') ? 'bg-gray-700 text-white' : '' }}">
                            🪑 Tables
                        </a>

                        <!-- DIUBAH -->
                        <!-- Orders -->
                        <a href="{{ route('staff.orders.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.orders.*') ? 'bg-gray-700 text-white' : '' }}">
                            🛒 Orders
                        </a>
                        <!-- SELESAI DIUBAH -->

                        <!-- Reservations -->
                        <a href="{{ route('staff.reservations.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.reservations.*') ? 'bg-gray-700 text-white' : '' }}">
                            🗓️ Reservations
                        </a>

                        <!-- Reports -->
                        <div x-data="{ open: {{ request()->routeIs('staff.reports.*') || request()->routeIs('staff.inventory.reports') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg">
                            <span>📈 Reports</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'transform rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" class="pl-4">
                            <a href="{{ route('staff.reports.sales') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.reports.sales') ? 'bg-gray-700' : '' }}">
                                Laporan Penjualan
                            </a>
                            <a href="{{ route('staff.inventory.reports') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg {{ request()->routeIs('staff.inventory.reports') ? 'bg-gray-700' : '' }}">
                                Laporan Inventaris
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation -->
                <header class="bg-white shadow">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">
                                @if (isset($header))
                                    {{ $header }}
                                @else
                                    Admin Dashboard
                                @endif
                            </h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

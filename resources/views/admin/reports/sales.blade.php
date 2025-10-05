<x-admin-layout>
    <x-slot name="header">
        Laporan Penjualan
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg mb-6 p-4">
                <form method="GET" action="{{ route('staff.reports.sales') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-gray-500">Total Pendapatan</h4>
                    <p class="text-3xl font-bold">USD {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-gray-500">Total Pesanan</h4>
                    <p class="text-3xl font-bold">{{ $totalOrders }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="text-lg font-semibold mb-4">Penjualan dari Waktu ke Waktu</h4>
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesData->pluck('date')) !!},
                datasets: [{
                    label: 'Total Penjualan',
                    data: {!! json_encode($salesData->pluck('total_sales')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-admin-layout>
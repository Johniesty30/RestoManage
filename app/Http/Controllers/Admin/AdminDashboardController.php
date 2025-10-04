<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\InventoryItem; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'active_reservations' => Reservation::where('status', 'confirmed')->count(),
            'today_revenue' => Order::whereDate('created_at', today())->sum('total_amount') ?? 0,
            'low_stock_items' => InventoryItem::lowStock()->count(), // Pastikan model InventoryItem di-import
            'out_of_stock_items' => InventoryItem::outOfStock()->count(), // Pastikan model InventoryItem di-import
        ];

        // Get low stock alerts
        $lowStockAlerts = InventoryItem::lowStock()
            ->orWhere->outOfStock()
            ->orderBy('current_stock')
            ->limit(5)
            ->get();

        // Mock recent activities (nanti bisa diganti dengan activity log real)
        $recent_activities = [
            [
                'user' => 'John Doe',
                'action' => 'Created new menu item',
                'time' => '2 minutes ago'
            ],
            [
                'user' => 'Jane Smith',
                'action' => 'Updated user permissions',
                'time' => '15 minutes ago'
            ],
            [
                'user' => 'Bob Wilson',
                'action' => 'Processed order #1234',
                'time' => '1 hour ago'
            ],
            [
                'user' => 'Alice Brown',
                'action' => 'Added new inventory item',
                'time' => '2 hours ago'
            ],
        ];

        // System information
        $system_info = [
            'last_backup' => 'Today, 02:00 AM',
            'storage_usage' => '45%',
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'recent_activities' => $recent_activities,
            'system_info' => $system_info,
            'lowStockAlerts' => $lowStockAlerts,
        ]);
    }
}

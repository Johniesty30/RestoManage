<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\MenuItem;
use App\Models\InventoryItem;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Table;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        return $this->redirectToRoleDashboard($user->role);
    }

    public function adminDashboard()
    {
        $stats = $this->getAdminStats();
        $recentOrders = Order::with(['customer', 'staff'])->latest()->take(5)->get();
        $lowStockItems = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->get();

        return view('dashboard', compact('stats', 'recentOrders', 'lowStockItems'));
    }

    public function managerDashboard()
    {
        $stats = $this->getManagerStats();
        $todayReservations = Reservation::whereDate('reservation_time', today())->with('customer')->get();
        $pendingOrders = Order::where('status', 'Pending')->with('orderItems.menuItem')->get();

        return view('dashboard.manager', compact('stats', 'todayReservations', 'pendingOrders'));
    }

    public function chefDashboard()
    {
        $pendingOrders = Order::with(['orderItems.menuItem'])
                            ->whereIn('status', ['Pending', 'In Progress'])
                            ->orderBy('order_time')
                            ->get();

        $ordersInProgress = Order::where('status', 'In Progress')->count();
        $ordersCompletedToday = Order::where('status', 'Completed')
                                    ->whereDate('order_time', today())
                                    ->count();

        return view('dashboard.chef', compact('pendingOrders', 'ordersInProgress', 'ordersCompletedToday'));
    }

    public function waiterDashboard()
    {
        $activeTables = Table::where('status', 'Occupied')->with('orders')->get();
        $pendingOrders = Order::where('status', 'Pending')->with('orderItems.menuItem')->get();
        $availableTables = Table::where('status', 'Available')->get();

        return view('dashboard.waiter', compact('activeTables', 'pendingOrders', 'availableTables'));
    }

    public function cashierDashboard()
    {
        $pendingPayments = Order::where('status', 'Completed')->with('customer')->get();
        $todayRevenue = Order::where('status', 'Paid')
                            ->whereDate('order_time', today())
                            ->sum('total_amount');
        $monthlyRevenue = Order::where('status', 'Paid')
                            ->whereMonth('order_time', now()->month)
                            ->sum('total_amount');

        return view('dashboard.cashier', compact('pendingPayments', 'todayRevenue', 'monthlyRevenue'));
    }

    public function customerDashboard()
    {
        $user = auth()->user();
        $customer = $user->customer;

        if (!$customer) {
            abort(403, 'Customer profile not found.');
        }

        $recentOrders = Order::where('customer_id', $customer->customer_id)
                            ->with('orderItems.menuItem')
                            ->latest()
                            ->take(5)
                            ->get();

        $upcomingReservations = Reservation::where('customer_id', $customer->customer_id)
                                        ->where('reservation_time', '>=', now())
                                        ->with('table')
                                        ->orderBy('reservation_time')
                                        ->take(5)
                                        ->get();

        $loyaltyPoints = $customer->loyalty_points;

        return view('dashboard.customer', compact('recentOrders', 'upcomingReservations', 'loyaltyPoints'));
    }

    private function redirectToRoleDashboard($role)
    {
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'chef' => redirect()->route('chef.dashboard'),
            'waiter' => redirect()->route('waiter.dashboard'),
            'cashier' => redirect()->route('cashier.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => redirect()->route('login'),
        };
    }

    private function getAdminStats()
    {
        return [
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('order_time', today())->count(),
            'total_revenue' => Order::where('status', 'Paid')->sum('total_amount'),
            'total_customers' => Customer::count(),
            'total_staff' => Staff::count(),
            'low_stock_items' => InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->count(),
            'active_reservations' => Reservation::where('status', 'Confirmed')->count(),
        ];
    }

    private function getManagerStats()
    {
        return [
            'today_orders' => Order::whereDate('order_time', today())->count(),
            'pending_orders' => Order::where('status', 'Pending')->count(),
            'today_reservations' => Reservation::whereDate('reservation_time', today())->count(),
            'available_tables' => Table::where('status', 'Available')->count(),
            'occupied_tables' => Table::where('status', 'Occupied')->count(),
            'total_revenue_today' => Order::where('status', 'Paid')
                                        ->whereDate('order_time', today())
                                        ->sum('total_amount'),
        ];
    }
}

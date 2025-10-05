<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        // Ganti relasi 'user' menjadi 'customer' dan 'staff' agar lebih jelas
        $orders = Order::with(['table', 'customer', 'staff'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $tables = Table::where('status', 'available')->get();
        $menuItems = MenuItem::all();
        $waiters = User::where('role', 'waiter')->get();
        return view('admin.orders.create', compact('tables', 'menuItems', 'waiters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'staff_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0; // Ganti nama variabel agar konsisten
        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            $totalAmount += $menuItem->price * $item['quantity'];
        }

        // --- PERBAIKAN ---
        // Membuat nomor pesanan unik secara otomatis
        $today = Carbon::now()->format('Ymd');
        $orderCountToday = Order::whereDate('created_at', Carbon::today())->count();
        $nextOrderNumber = $orderCountToday + 1;
        $orderNumber = 'ORD-' . $today . '-' . str_pad($nextOrderNumber, 4, '0', STR_PAD_LEFT);
        // --- SELESAI PERBAIKAN ---

        $order = Order::create([
            'table_id' => $request->table_id,
            'customer_id' => auth()->user()->id,
            'staff_id' => $request->staff_id,
            // --- PERBAIKAN ---
            // Menambahkan order_number ke data yang disimpan
            'order_number' => $orderNumber,
            // --- SELESAI PERBAIKAN ---
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'order_time' => Carbon::now(),
        ]);

        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            $order->items()->create([
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price' => $menuItem->price,
            ]);
        }

        $table = Table::find($request->table_id);
        $table->update(['status' => 'occupied']);

        return redirect()->route('staff.orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load('items.menuItem', 'table', 'customer', 'staff');
        return view('admin.orders.show', compact('order'));
    }
}
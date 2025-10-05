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

        // Membuat nomor pesanan unik
        $today = Carbon::now()->format('Ymd');
        $orderCountToday = Order::whereDate('created_at', Carbon::today())->count();
        $nextOrderNumber = $orderCountToday + 1;
        $orderNumber = 'ORD-' . $today . '-' . str_pad($nextOrderNumber, 4, '0', STR_PAD_LEFT);

        // Buat pesanan dengan total_amount awal 0
        $order = Order::create([
            'table_id' => $request->table_id,
            'customer_id' => auth()->user()->id,
            'staff_id' => $request->staff_id,
            'order_number' => $orderNumber,
            'total_amount' => 0, // Inisialisasi total amount
            'status' => 'pending',
            'order_time' => Carbon::now(),
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            $subtotal = $menuItem->price * $item['quantity'];
            
            $order->items()->create([
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $menuItem->price, // Menggunakan 'unit_price'
                'subtotal' => $subtotal,
            ]);

            $totalAmount += $subtotal;
        }

        // Update total_amount pada pesanan
        $order->total_amount = $totalAmount;
        $order->save();

        $table = Table::find($request->table_id);
        $table->update(['status' => 'occupied']);

        return redirect()->route('staff.orders.index')->with('success', 'Order created successfully.');
    }
    public function show(Order $order)
    {
        $order->load('items.menuItem', 'table', 'customer', 'staff');
        return view('admin.orders.show', compact('order'));
    }
     public function edit(Order $order)
    {
        $tables = Table::all();
        $waiters = User::where('role', 'waiter')->get();
        return view('admin.orders.edit', compact('order', 'tables', 'waiters'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'staff_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $order->update([
            'table_id' => $request->table_id,
            'staff_id' => $request->staff_id,
            'status' => $request->status,
        ]);

        return redirect()->route('staff.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('staff.orders.index')->with('success', 'Order deleted successfully.');
    }
}
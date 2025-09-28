<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Customer;
use App\Models\Table;
use App\Models\Staff;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'table', 'staff', 'orderItems.menuItem'])
                      ->latest()
                      ->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $tables = Table::where('status', 'Available')->get();
        $staff = Staff::where('is_active', true)->get();
        $menuItems = MenuItem::where('is_available', true)->get();

        return view('orders.create', compact('customers', 'tables', 'staff', 'menuItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,customer_id',
            'table_id' => 'nullable|exists:tables,table_id',
            'staff_id' => 'required|exists:staff,staff_id',
            'order_type' => 'required|in:Dine-In,Takeaway',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:menu_items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate total amount
        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['item_id']);
            $subtotal = $menuItem->price * $item['quantity'];
            $totalAmount += $subtotal;

            $orderItems[] = [
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];
        }

        // Create order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'table_id' => $request->table_id,
            'staff_id' => $request->staff_id,
            'order_type' => $request->order_type,
            'total_amount' => $totalAmount,
            'status' => 'Pending',
        ]);

        // Create order items
        foreach ($orderItems as $orderItem) {
            $order->orderItems()->create($orderItem);
        }

        // Update table status if it's a dine-in order
        if ($request->order_type === 'Dine-In' && $request->table_id) {
            Table::find($request->table_id)->update(['status' => 'Occupied']);
        }

        return redirect()->route('orders.index')
                        ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'table', 'staff', 'orderItems.menuItem']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $customers = Customer::all();
        $tables = Table::all();
        $staff = Staff::where('is_active', true)->get();
        $menuItems = MenuItem::where('is_available', true)->get();
        $order->load('orderItems');

        return view('orders.edit', compact('order', 'customers', 'tables', 'staff', 'menuItems'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,customer_id',
            'table_id' => 'nullable|exists:tables,table_id',
            'staff_id' => 'required|exists:staff,staff_id',
            'order_type' => 'required|in:Dine-In,Takeaway',
            'status' => 'required|in:Pending,In Progress,Completed,Paid,Cancelled',
        ]);

        $oldTableId = $order->table_id;
        $newTableId = $request->table_id;

        $order->update($request->all());

        // Handle table status changes
        if ($oldTableId != $newTableId) {
            if ($oldTableId) {
                Table::find($oldTableId)->update(['status' => 'Available']);
            }
            if ($newTableId && $request->order_type === 'Dine-In') {
                Table::find($newTableId)->update(['status' => 'Occupied']);
            }
        }

        return redirect()->route('orders.index')
                        ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        // Free up the table if it's a dine-in order
        if ($order->table_id) {
            $order->table->update(['status' => 'Available']);
        }

        $order->orderItems()->delete();
        $order->delete();

        return redirect()->route('orders.index')
                        ->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Order $order, $status)
    {
        $validStatuses = ['Pending', 'In Progress', 'Completed', 'Paid', 'Cancelled'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $order->update(['status' => $status]);

        // If order is completed or cancelled, free up the table
        if (in_array($status, ['Completed', 'Paid', 'Cancelled']) && $order->table_id) {
            $order->table->update(['status' => 'Available']);
        }

        // If order is paid, add loyalty points
        if ($status === 'Paid' && $order->customer_id) {
            $points = floor($order->total_amount / 1000); // 1 point per 1000 IDR
            $order->customer->increment('loyalty_points', $points);
        }

        return redirect()->route('orders.index')
                        ->with('success', "Order status updated to {$status}.");
    }

    public function addItem(Request $request, Order $order)
    {
        $request->validate([
            'item_id' => 'required|exists:menu_items,item_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menuItem = MenuItem::find($request->item_id);
        $subtotal = $menuItem->price * $request->quantity;

        // Create order item
        $order->orderItems()->create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'subtotal' => $subtotal,
        ]);

        // Update order total
        $order->increment('total_amount', $subtotal);

        return redirect()->route('orders.show', $order)
                        ->with('success', 'Item added to order successfully.');
    }

    public function kitchenOrders()
    {
        $orders = Order::with(['orderItems.menuItem'])
                      ->whereIn('status', ['Pending', 'In Progress'])
                      ->latest()
                      ->get();

        return view('orders.kitchen', compact('orders'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $stock_status = $request->get('stock_status');

        $inventoryItems = InventoryItem::when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('unit_of_measure', 'like', "%{$search}%");
            })
            ->when($stock_status, function($query, $stock_status) {
                if ($stock_status === 'low_stock') {
                    return $query->lowStock();
                } elseif ($stock_status === 'out_of_stock') {
                    return $query->outOfStock();
                } elseif ($stock_status === 'in_stock') {
                    return $query->whereRaw('current_stock > min_stock')
                               ->where('current_stock', '>', 0);
                }
            })
            ->orderBy('name')
            ->paginate(15);

        $lowStockCount = InventoryItem::lowStock()->count();
        $outOfStockCount = InventoryItem::outOfStock()->count();

        return view('admin.inventory.index', compact(
            'inventoryItems',
            'search',
            'stock_status',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
        ]);

        InventoryItem::create([
            'name' => $request->name,
            'current_stock' => $request->current_stock,
            'min_stock' => $request->min_stock,
            'unit_of_measure' => $request->unit_of_measure,
        ]);

        return redirect()->route('staff.inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(InventoryItem $inventoryItem)
    {
        return view('admin.inventory.show', compact('inventoryItem'));
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(InventoryItem $inventoryItem)
    {
        return view('admin.inventory.edit', compact('inventoryItem'));
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
        ]);

        $inventoryItem->update([
            'name' => $request->name,
            'current_stock' => $request->current_stock,
            'min_stock' => $request->min_stock,
            'unit_of_measure' => $request->unit_of_measure,
        ]);

        return redirect()->route('staff.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();

        return redirect()->route('staff.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Show form for updating stock
     */
    public function showStockUpdate(InventoryItem $inventoryItem)
    {
        return view('admin.inventory.stock-update', compact('inventoryItem'));
    }

    /**
     * Update stock for inventory item
     */
    public function updateStock(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'action' => ['required', 'in:add,reduce,set'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStock = $inventoryItem->current_stock;

        switch ($request->action) {
            case 'add':
                $inventoryItem->addStock($request->quantity);
                $action = 'added';
                break;

            case 'reduce':
                $success = $inventoryItem->reduceStock($request->quantity);
                if (!$success) {
                    return redirect()->back()
                        ->with('error', 'Cannot reduce stock. Insufficient current stock.')
                        ->withInput();
                }
                $action = 'reduced';
                break;

            case 'set':
                $inventoryItem->update(['current_stock' => $request->quantity]);
                $action = 'set to';
                break;
        }

        // Here you can log the stock transaction if needed
        // StockTransaction::create([...]);

        $message = "Stock {$action} successfully. ";
        $message .= "Stock changed from {$oldStock} to {$inventoryItem->current_stock} {$inventoryItem->unit_of_measure}";

        return redirect()->route('staff.inventory.show', $inventoryItem)
            ->with('success', $message);
    }

    /**
     * Generate inventory reports
     */
    public function reports()
    {
        $lowStockItems = InventoryItem::lowStock()->get();
        $outOfStockItems = InventoryItem::outOfStock()->get();

        $stockSummary = [
            'total_items' => InventoryItem::count(),
            'low_stock_count' => $lowStockItems->count(),
            'out_of_stock_count' => $outOfStockItems->count(),
            'total_inventory_value' => 0, // You can add price field later
        ];

        return view('admin.inventory.reports', compact(
            'lowStockItems',
            'outOfStockItems',
            'stockSummary'
        ));
    }

    /**
     * Get low stock alerts for dashboard
     */
    public function getLowStockAlerts()
    {
        return InventoryItem::lowStock()
            ->orWhere->outOfStock()
            ->orderBy('current_stock')
            ->limit(5)
            ->get();
    }
}

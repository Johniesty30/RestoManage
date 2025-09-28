<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = InventoryItem::with('menuItems')->get();
        $lowStockItems = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->get();

        return view('inventory.index', compact('inventoryItems', 'lowStockItems'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150|unique:inventory_items',
            'quantity_in_stock' => 'required|numeric|min:0',
            'unit_of_measure' => 'required|max:50',
            'reorder_level' => 'nullable|numeric|min:0',
        ]);

        InventoryItem::create($request->all());

        return redirect()->route('inventory.index')
                        ->with('success', 'Inventory item created successfully.');
    }

    public function show(InventoryItem $inventoryItem)
    {
        $inventoryItem->load('menuItems');
        return view('inventory.show', compact('inventoryItem'));
    }

    public function edit(InventoryItem $inventoryItem)
    {
        return view('inventory.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'name' => 'required|max:150|unique:inventory_items,name,' . $inventoryItem->inventory_item_id . ',inventory_item_id',
            'quantity_in_stock' => 'required|numeric|min:0',
            'unit_of_measure' => 'required|max:50',
            'reorder_level' => 'nullable|numeric|min:0',
        ]);

        $inventoryItem->update($request->all());

        return redirect()->route('inventory.index')
                        ->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        if ($inventoryItem->menuItems()->count() > 0) {
            return redirect()->route('inventory.index')
                            ->with('error', 'Cannot delete inventory item used in menu items.');
        }

        $inventoryItem->delete();

        return redirect()->route('inventory.index')
                        ->with('success', 'Inventory item deleted successfully.');
    }

    public function restock(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0',
        ]);

        $inventoryItem->increment('quantity_in_stock', $request->quantity);

        return redirect()->route('inventory.index')
                        ->with('success', "Inventory item restocked with {$request->quantity} {$inventoryItem->unit_of_measure}.");
    }

    public function lowStock()
    {
        $lowStockItems = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->get();
        return view('inventory.low-stock', compact('lowStockItems'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with(['category', 'ingredients'])->get();
        return view('menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        $categories = Category::all();
        $inventoryItems = InventoryItem::all();
        return view('menu-items.create', compact('categories', 'inventoryItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'is_available' => 'boolean',
        ]);

        $menuItem = MenuItem::create($request->all());

        // Attach ingredients if provided
        if ($request->has('ingredients')) {
            $ingredients = [];
            foreach ($request->ingredients as $inventoryId => $quantity) {
                if ($quantity > 0) {
                    $ingredients[$inventoryId] = ['quantity_used' => $quantity];
                }
            }
            $menuItem->ingredients()->sync($ingredients);
        }

        return redirect()->route('menu-items.index')
                        ->with('success', 'Menu item created successfully.');
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load(['category', 'ingredients', 'orderItems']);
        return view('menu-items.show', compact('menuItem'));
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::all();
        $inventoryItems = InventoryItem::all();
        $menuItem->load('ingredients');

        return view('menu-items.edit', compact('menuItem', 'categories', 'inventoryItems'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required|max:150',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'is_available' => 'boolean',
        ]);

        $menuItem->update($request->all());

        // Update ingredients
        if ($request->has('ingredients')) {
            $ingredients = [];
            foreach ($request->ingredients as $inventoryId => $quantity) {
                if ($quantity > 0) {
                    $ingredients[$inventoryId] = ['quantity_used' => $quantity];
                }
            }
            $menuItem->ingredients()->sync($ingredients);
        }

        return redirect()->route('menu-items.index')
                        ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->orderItems()->count() > 0) {
            return redirect()->route('menu-items.index')
                            ->with('error', 'Cannot delete menu item with order history.');
        }

        $menuItem->ingredients()->detach();
        $menuItem->delete();

        return redirect()->route('menu-items.index')
                        ->with('success', 'Menu item deleted successfully.');
    }

    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->update(['is_available' => !$menuItem->is_available]);

        return redirect()->route('menu-items.index')
                        ->with('success', 'Menu item availability updated.');
    }
}

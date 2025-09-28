<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::withCount(['orders', 'reservations'])->get();
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:tables|max:10',
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:Available,Occupied,Reserved,Maintenance',
        ]);

        Table::create($request->all());

        return redirect()->route('tables.index')
                        ->with('success', 'Table created successfully.');
    }

    public function show(Table $table)
    {
        $table->load(['orders', 'reservations.customer']);
        return view('tables.show', compact('table'));
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|max:10|unique:tables,table_number,' . $table->table_id . ',table_id',
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:Available,Occupied,Reserved,Maintenance',
        ]);

        $table->update($request->all());

        return redirect()->route('tables.index')
                        ->with('success', 'Table updated successfully.');
    }

    public function destroy(Table $table)
    {
        if ($table->orders()->count() > 0 || $table->reservations()->count() > 0) {
            return redirect()->route('tables.index')
                            ->with('error', 'Cannot delete table with order or reservation history.');
        }

        $table->delete();

        return redirect()->route('tables.index')
                        ->with('success', 'Table deleted successfully.');
    }

    public function availability()
    {
        $availableTables = Table::where('status', 'Available')->get();
        $occupiedTables = Table::where('status', 'Occupied')->get();
        $reservedTables = Table::where('status', 'Reserved')->get();

        return view('tables.availability', compact('availableTables', 'occupiedTables', 'reservedTables'));
    }
}

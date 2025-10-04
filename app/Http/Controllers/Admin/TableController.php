<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    /**
     * Display a listing of the tables.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $tables = Table::when($search, function($query, $search) {
                return $query->where('table_number', 'like', "%{$search}%");
            })
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->withCount(['reservations' => function($query) {
                $query->where('reservation_time', '>=', now())
                      ->whereIn('status', ['confirmed', 'seated']);
            }])
            ->orderBy('table_number')
            ->paginate(12);

        $statuses = ['available', 'occupied', 'reserved', 'maintenance'];
        $tableStats = [
            'total' => Table::count(),
            'available' => Table::available()->count(),
            'occupied' => Table::occupied()->count(),
            'reserved' => Table::reserved()->count(),
        ];

        return view('admin.tables.index', compact(
            'tables',
            'search',
            'status',
            'statuses',
            'tableStats'
        ));
    }

    /**
     * Show the form for creating a new table.
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Store a newly created table in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => ['required', 'string', 'max:50', 'unique:tables'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'in:available,occupied,reserved,maintenance'],
            'location' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Table::create([
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'status' => $request->status,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()->route('staff.tables.index')
            ->with('success', 'Table created successfully.');
    }

    /**
     * Display the specified table.
     */
    public function show(Table $table)
    {
        $table->load(['reservations' => function($query) {
            $query->where('reservation_time', '>=', now())
                  ->orderBy('reservation_time');
        }, 'orders' => function($query) {
            $query->whereDate('created_at', today())
                  ->orderBy('created_at', 'desc');
        }]);

        return view('admin.tables.show', compact('table'));
    }

    /**
     * Show the form for editing the specified table.
     */
    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    /**
     * Update the specified table in storage.
     */
    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => ['required', 'string', 'max:50', 'unique:tables,table_number,' . $table->id],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'in:available,occupied,reserved,maintenance'],
            'location' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $table->update([
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'status' => $request->status,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()->route('staff.tables.index')
            ->with('success', 'Table updated successfully.');
    }

    /**
     * Remove the specified table from storage.
     */
    public function destroy(Table $table)
    {
        // Check if table has active reservations
        if ($table->reservations()->whereIn('status', ['confirmed', 'seated'])->exists()) {
            return redirect()->route('staff.tables.index')
                ->with('error', 'Cannot delete table with active reservations. Please cancel or complete the reservations first.');
        }

        // Check if table has active orders
        if ($table->orders()->whereIn('status', ['pending', 'preparing', 'ready'])->exists()) {
            return redirect()->route('staff.tables.index')
                ->with('error', 'Cannot delete table with active orders. Please complete the orders first.');
        }

        $table->delete();

        return redirect()->route('staff.tables.index')
            ->with('success', 'Table deleted successfully.');
    }

    /**
     * Toggle table status
     */
    public function toggleStatus(Table $table)
    {
        $newStatus = $table->status === 'available' ? 'maintenance' : 'available';

        $table->update(['status' => $newStatus]);

        $status = $newStatus === 'available' ? 'available' : 'under maintenance';

        return redirect()->route('staff.tables.index')
            ->with('success', "Table marked as {$status}.");
    }

    /**
     * Check table availability
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required'],
            'guests' => ['required', 'integer', 'min:1'],
            'duration' => ['required', 'integer', 'min:1', 'max:4'], // hours
        ]);

        $reservationTime = \Carbon\Carbon::parse($request->date . ' ' . $request->time);
        $endTime = $reservationTime->copy()->addHours($request->duration);

        $availableTables = Table::available()
            ->byCapacity($request->guests)
            ->whereDoesntHave('reservations', function($query) use ($reservationTime, $endTime) {
                $query->whereIn('status', ['confirmed', 'seated'])
                      ->where(function($q) use ($reservationTime, $endTime) {
                          $q->whereBetween('reservation_time', [$reservationTime, $endTime])
                            ->orWhere(function($q2) use ($reservationTime, $endTime) {
                                $q2->where('reservation_time', '<', $reservationTime)
                                   ->whereRaw('DATE_ADD(reservation_time, INTERVAL 2 HOUR) > ?', [$reservationTime]);
                            });
                      });
            })
            ->get();

        return view('admin.tables.availability-results', compact(
            'availableTables',
            'reservationTime',
            'endTime',
            'request'
        ));
    }

    /**
     * Show availability checker form
     */
    public function showAvailabilityChecker()
    {
        return view('admin.tables.availability-checker');
    }
}

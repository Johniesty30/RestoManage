<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $date = $request->get('date', today()->format('Y-m-d'));

        $reservations = Reservation::with(['customer', 'table'])
            ->when($search, function($query, $search) {
                return $query->whereHas('customer', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($date, function($query, $date) {
                return $query->whereDate('reservation_time', $date);
            })
            ->orderBy('reservation_time')
            ->paginate(15);

        $statuses = ['pending', 'confirmed', 'seated', 'completed', 'cancelled'];
        $reservationStats = [
            'today' => Reservation::whereDate('reservation_time', today())->count(),
            'upcoming' => Reservation::upcoming()->count(),
            'pending' => Reservation::where('status', 'pending')->count(),
        ];

        return view('admin.reservations.index', compact(
            'reservations',
            'search',
            'status',
            'date',
            'statuses',
            'reservationStats'
        ));
    }

    /**
     * Show the form for creating a new reservation.
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $tables = Table::available()->orderBy('table_number')->get();

        return view('admin.reservations.create', compact('customers', 'tables'));
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'table_id' => ['required', 'exists:tables,id'],
            'reservation_time' => ['required', 'date'],
            'guests' => ['required', 'integer', 'min:1'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        // Check table availability
        $reservationTime = Carbon::parse($request->reservation_time);
        $table = Table::find($request->table_id);

        if (!$this->isTableAvailable($table, $reservationTime)) {
            return redirect()->back()
                ->with('error', 'Selected table is not available at the chosen time. Please choose a different table or time.')
                ->withInput();
        }

        // Check table capacity
        if ($table->capacity < $request->guests) {
            return redirect()->back()
                ->with('error', "Selected table can only accommodate {$table->capacity} guests. Please choose a larger table.")
                ->withInput();
        }

        Reservation::create([
            'customer_id' => $request->customer_id,
            'table_id' => $request->table_id,
            'reservation_time' => $reservationTime,
            'guests' => $request->guests,
            'special_requests' => $request->special_requests,
            'status' => 'confirmed',
        ]);

        // Update table status
        $table->update(['status' => 'reserved']);

        return redirect()->route('staff.reservations.index')
            ->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified reservation.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['customer', 'table']);
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified reservation.
     */
    public function edit(Reservation $reservation)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $tables = Table::active()->orderBy('table_number')->get();

        return view('admin.reservations.edit', compact('reservation', 'customers', 'tables'));
    }

    /**
     * Update the specified reservation in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'table_id' => ['required', 'exists:tables,id'],
            'reservation_time' => ['required', 'date'],
            'guests' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:pending,confirmed,seated,completed,cancelled'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        $reservationTime = Carbon::parse($request->reservation_time);

        // If table changed, check availability
        if ($reservation->table_id != $request->table_id) {
            $newTable = Table::find($request->table_id);

            if (!$this->isTableAvailable($newTable, $reservationTime, $reservation->id)) {
                return redirect()->back()
                    ->with('error', 'Selected table is not available at the chosen time.')
                    ->withInput();
            }

            // Check capacity
            if ($newTable->capacity < $request->guests) {
                return redirect()->back()
                    ->with('error', "Selected table can only accommodate {$newTable->capacity} guests.")
                    ->withInput();
            }

            // Release old table if it was reserved
            if ($reservation->table->status === 'reserved') {
                $reservation->table->update(['status' => 'available']);
            }

            // Reserve new table
            $newTable->update(['status' => 'reserved']);
        }

        $reservation->update([
            'customer_id' => $request->customer_id,
            'table_id' => $request->table_id,
            'reservation_time' => $reservationTime,
            'guests' => $request->guests,
            'status' => $request->status,
            'special_requests' => $request->special_requests,
        ]);

        return redirect()->route('staff.reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified reservation from storage.
     */
    public function destroy(Reservation $reservation)
    {
        // Free up the table
        if ($reservation->table->status === 'reserved') {
            $reservation->table->update(['status' => 'available']);
        }

        $reservation->delete();

        return redirect()->route('staff.reservations.index')
            ->with('success', 'Reservation deleted successfully.');
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->status === 'cancelled') {
            return redirect()->back()->with('error', 'Reservation is already cancelled.');
        }

        // Free up the table
        if ($reservation->table->status === 'reserved') {
            $reservation->table->update(['status' => 'available']);
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('staff.reservations.index')
            ->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * Mark reservation as seated
     */
    public function markAsSeated(Reservation $reservation)
    {
        $reservation->update(['status' => 'seated']);
        $reservation->table->update(['status' => 'occupied']);

        return redirect()->route('staff.reservations.index')
            ->with('success', 'Reservation marked as seated.');
    }

    /**
     * Mark reservation as completed
     */
    public function markAsCompleted(Reservation $reservation)
    {
        $reservation->update(['status' => 'completed']);
        $reservation->table->update(['status' => 'available']);

        return redirect()->route('staff.reservations.index')
            ->with('success', 'Reservation marked as completed.');
    }

    /**
     * Show calendar view
     */
    public function calendar(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $reservations = Reservation::with(['customer', 'table'])
            ->whereDate('reservation_time', $selectedDate)
            ->orderBy('reservation_time')
            ->get()
            ->groupBy(function($reservation) {
                return $reservation->reservation_time->format('H:00');
            });

        return view('admin.reservations.calendar', compact('reservations', 'selectedDate'));
    }

    /**
     * Auto-assign table based on availability
     */
    public function autoAssign(Request $request)
    {
        $request->validate([
            'reservation_time' => ['required', 'date'],
            'guests' => ['required', 'integer', 'min:1'],
        ]);

        $reservationTime = Carbon::parse($request->reservation_time);
        $availableTable = $this->findAvailableTable($reservationTime, $request->guests);

        if ($availableTable) {
            return response()->json([
                'success' => true,
                'table' => $availableTable,
                'message' => "Table {$availableTable->table_number} is available and can accommodate {$request->guests} guests."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No available tables found for the selected time and number of guests.'
        ], 404);
    }

    /**
     * Check if table is available at given time
     */
    private function isTableAvailable(Table $table, Carbon $reservationTime, $excludeReservationId = null)
    {
        $query = $table->reservations()
            ->whereIn('status', ['confirmed', 'seated'])
            ->whereDate('reservation_time', $reservationTime->toDateString())
            ->where(function($q) use ($reservationTime) {
                $q->whereBetween('reservation_time', [
                    $reservationTime->copy()->subHours(2),
                    $reservationTime->copy()->addHours(2)
                ]);
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return !$query->exists();
    }

    /**
     * Find available table for given time and guests
     */
    private function findAvailableTable(Carbon $reservationTime, $guests)
    {
        return Table::available()
            ->byCapacity($guests)
            ->get()
            ->filter(function($table) use ($reservationTime) {
                return $this->isTableAvailable($table, $reservationTime);
            })
            ->sortBy('capacity')
            ->first();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Table;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['customer', 'table'])->latest()->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $customers = Customer::all();
        $tables = Table::where('status', 'Available')->get();
        return view('reservations.create', compact('customers', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'table_id' => 'required|exists:tables,table_id',
            'reservation_time' => 'required|date|after:now',
            'number_of_guests' => 'required|integer|min:1',
        ]);

        // Check if table is available
        $table = Table::find($request->table_id);
        if ($table->status !== 'Available') {
            return redirect()->back()
                            ->with('error', 'Selected table is not available.');
        }

        // Check if table capacity is sufficient
        if ($table->capacity < $request->number_of_guests) {
            return redirect()->back()
                            ->with('error', 'Table capacity is insufficient for the number of guests.');
        }

        $reservation = Reservation::create($request->all());

        // Update table status
        $table->update(['status' => 'Reserved']);

        return redirect()->route('reservations.index')
                        ->with('success', 'Reservation created successfully.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['customer', 'table']);
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $customers = Customer::all();
        $tables = Table::all();
        $reservation->load(['customer', 'table']);

        return view('reservations.edit', compact('reservation', 'customers', 'tables'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'table_id' => 'required|exists:tables,table_id',
            'reservation_time' => 'required|date',
            'number_of_guests' => 'required|integer|min:1',
            'status' => 'required|in:Confirmed,Cancelled,Completed',
        ]);

        $oldTableId = $reservation->table_id;
        $newTableId = $request->table_id;

        $reservation->update($request->all());

        // Update table status if table changed
        if ($oldTableId != $newTableId) {
            Table::find($oldTableId)->update(['status' => 'Available']);
            Table::find($newTableId)->update(['status' => 'Reserved']);
        }

        return redirect()->route('reservations.index')
                        ->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        // Free up the table
        $reservation->table->update(['status' => 'Available']);

        $reservation->delete();

        return redirect()->route('reservations.index')
                        ->with('success', 'Reservation deleted successfully.');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status' => 'Cancelled']);
        $reservation->table->update(['status' => 'Available']);

        return redirect()->route('reservations.index')
                        ->with('success', 'Reservation cancelled successfully.');
    }

    public function complete(Reservation $reservation)
    {
        $reservation->update(['status' => 'Completed']);
        $reservation->table->update(['status' => 'Available']);

        return redirect()->route('reservations.index')
                        ->with('success', 'Reservation completed successfully.');
    }
}

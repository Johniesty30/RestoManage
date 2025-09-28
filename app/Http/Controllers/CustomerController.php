<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount(['orders', 'reservations'])->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'phone_number' => 'required|unique:customers|max:20',
            'email' => 'nullable|email|unique:customers',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
                        ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['orders', 'reservations.table']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|max:150',
            'phone_number' => 'required|max:20|unique:customers,phone_number,' . $customer->customer_id . ',customer_id',
            'email' => 'nullable|email|unique:customers,email,' . $customer->customer_id . ',customer_id',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
                        ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->orders()->count() > 0 || $customer->reservations()->count() > 0) {
            return redirect()->route('customers.index')
                            ->with('error', 'Cannot delete customer with order or reservation history.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
                        ->with('success', 'Customer deleted successfully.');
    }

    public function loyaltyReport()
    {
        $topCustomers = Customer::orderBy('loyalty_points', 'desc')->take(10)->get();
        return view('customers.loyalty-report', compact('topCustomers'));
    }
}

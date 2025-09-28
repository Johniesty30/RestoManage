<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Role;
use App\Models\Schedule;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with(['role', 'schedules'])->get();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'role_id' => 'required|exists:roles,role_id',
            'phone_number' => 'nullable|max:20',
            'is_active' => 'boolean',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')
                        ->with('success', 'Staff member created successfully.');
    }

    public function show(Staff $staff)
    {
        $staff->load(['role', 'schedules', 'orders']);
        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $roles = Role::all();
        return view('staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|max:150',
            'role_id' => 'required|exists:roles,role_id',
            'phone_number' => 'nullable|max:20',
            'is_active' => 'boolean',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.index')
                        ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->orders()->count() > 0) {
            return redirect()->route('staff.index')
                            ->with('error', 'Cannot delete staff member with order history.');
        }

        $staff->schedules()->delete();
        $staff->delete();

        return redirect()->route('staff.index')
                        ->with('success', 'Staff member deleted successfully.');
    }

    public function toggleActive(Staff $staff)
    {
        $staff->update(['is_active' => !$staff->is_active]);

        $status = $staff->is_active ? 'activated' : 'deactivated';

        return redirect()->route('staff.index')
                        ->with('success', "Staff member {$status} successfully.");
    }

    public function schedules(Staff $staff)
    {
        $schedules = $staff->schedules()->orderBy('start_time')->get();
        return view('staff.schedules', compact('staff', 'schedules'));
    }

    public function addSchedule(Request $request, Staff $staff)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Schedule::create([
            'staff_id' => $staff->staff_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('staff.schedules', $staff)
                        ->with('success', 'Schedule added successfully.');
    }
}

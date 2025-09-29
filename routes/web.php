<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes (Default Breeze)
Route::get('/dashboard', function () {
    // Redirect to appropriate dashboard based on role
    $user = auth()->user();

    if ($user->isStaff()) {
        return redirect()->route('staff.dashboard');
    } else {
        return redirect()->route('customer.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Staff Portal Routes (All staff roles)
Route::prefix('staff')->name('staff.')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });
});

// Customer Portal Routes
Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
});

// Role-specific dashboard redirects
Route::get('/admin/dashboard', function () {
    return redirect()->route('staff.admin.dashboard');
})->middleware(['auth', 'admin']);

Route::get('/manager/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth', 'manager']);

Route::get('/chef/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth', 'chef']);

Route::get('/waiter/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth', 'waiter']);

Route::get('/cashier/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth', 'cashier']);

require __DIR__.'/auth.php';

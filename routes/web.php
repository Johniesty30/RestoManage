<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // Tambahkan ini
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Arahkan route /dashboard utama ke controller untuk pengecekan role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grup untuk route dashboard berdasarkan role
Route::middleware(['auth', 'verified'])->group(function() {
    // Sesuaikan middleware 'role' dengan implementasi Anda, contoh: 'role:admin'
    Route::get('admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('manager/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
    Route::get('chef/dashboard', [DashboardController::class, 'chefDashboard'])->name('chef.dashboard');
    Route::get('waiter/dashboard', [DashboardController::class, 'waiterDashboard'])->name('waiter.dashboard');
    Route::get('cashier/dashboard', [DashboardController::class, 'cashierDashboard'])->name('cashier.dashboard');
    Route::get('customer/dashboard', [DashboardController::class, 'customerDashboard'])->name('customer.dashboard');
});


require __DIR__.'/auth.php';

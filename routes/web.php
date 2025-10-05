<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/', function () {
    return view('welcome');
});

// Default Breeze Dashboard (redirect berdasarkan role)
Route::get('/dashboard', function () {
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

// Staff Portal Routes
Route::prefix('staff')->name('staff.')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Inventory Management Routes
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/create', [InventoryController::class, 'create'])->name('create');
            Route::post('/', [InventoryController::class, 'store'])->name('store');
            Route::get('/{inventoryItem}', [InventoryController::class, 'show'])->name('show');
            Route::get('/{inventoryItem}/edit', [InventoryController::class, 'edit'])->name('edit');
            Route::put('/{inventoryItem}', [InventoryController::class, 'update'])->name('update');
            Route::delete('/{inventoryItem}', [InventoryController::class, 'destroy'])->name('destroy');
            Route::get('/{inventoryItem}/stock-update', [InventoryController::class, 'showStockUpdate'])->name('stock-update');
            Route::post('/{inventoryItem}/update-stock', [InventoryController::class, 'updateStock'])->name('update-stock');
            Route::get('/reports/stock', [InventoryController::class, 'reports'])->name('reports');
        });

        // User Management Routes
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Category Management Routes
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        // Menu Item Management Routes
        Route::prefix('menu-items')->name('menu-items.')->group(function () {
            Route::get('/', [MenuItemController::class, 'index'])->name('index');
            Route::get('/create', [MenuItemController::class, 'create'])->name('create');
            Route::post('/', [MenuItemController::class, 'store'])->name('store');
            Route::get('/{menuItem}', [MenuItemController::class, 'show'])->name('show');
            Route::get('/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('edit');
            Route::put('/{menuItem}', [MenuItemController::class, 'update'])->name('update');
            Route::delete('/{menuItem}', [MenuItemController::class, 'destroy'])->name('destroy');
            Route::patch('/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])->name('toggle-availability');
        });

        // Table Management Routes
        Route::prefix('tables')->name('tables.')->group(function () {
            Route::get('/', [TableController::class, 'index'])->name('index');
            Route::get('/create', [TableController::class, 'create'])->name('create');
            Route::post('/', [TableController::class, 'store'])->name('store');
            Route::get('/{table}', [TableController::class, 'show'])->name('show');
            Route::get('/{table}/edit', [TableController::class, 'edit'])->name('edit');
            Route::put('/{table}', [TableController::class, 'update'])->name('update');
            Route::delete('/{table}', [TableController::class, 'destroy'])->name('destroy');
            Route::patch('/{table}/toggle-status', [TableController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/availability/checker', [TableController::class, 'showAvailabilityChecker'])->name('availability-checker');
            Route::post('/availability/check', [TableController::class, 'checkAvailability'])->name('check-availability');
        });
        
        // Reservation Management Routes
         Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/calendar', [ReservationController::class, 'calendar'])->name('calendar');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::post('/auto-assign', [ReservationController::class, 'autoAssign'])->name('auto-assign'); // TAMBAHKAN BARIS INI
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
    });

        // Order Management Routes (BARU)
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/create', [OrderController::class, 'create'])->name('create');
            Route::post('/', [OrderController::class, 'store'])->name('store');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
            Route::put('/{order}', [OrderController::class, 'update'])->name('update');
            Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
        });

        // Rute untuk Laporan
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('sales');
});
    });
});

// Customer Portal Routes
Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    // Route customer lainnya di sini
});

require __DIR__.'/auth.php';

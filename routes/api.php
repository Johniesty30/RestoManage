<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Tambahkan baris ini
Route::post('/customers', [CustomerController::class, 'store']);

// Anda juga bisa mendaftarkan semua rute --api sekaligus
// Route::apiResource('/customers', CustomerController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
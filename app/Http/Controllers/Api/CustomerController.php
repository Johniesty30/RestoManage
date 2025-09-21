<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest; // 1. Import Request
use App\Models\Customer;                    // 2. Import Model
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    // ... metode lain seperti index(), show() ...

    /**
     * Menyimpan data customer baru.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        // 3. Validasi terjadi otomatis karena StoreCustomerRequest
        
        // 4. Ambil data yang sudah divalidasi
        $validatedData = $request->validated();

        // 5. Buat customer baru
        $customer = Customer::create($validatedData);

        // 6. Kembalikan respon JSON
        return response()->json([
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201); // 201 = Created
    }

    // ... metode lain seperti update(), destroy() ...
}
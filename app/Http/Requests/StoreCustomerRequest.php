<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        // Ubah ke true agar semua orang bisa membuat request
        // Anda bisa menambahkan logika otorisasi di sini nanti
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:customers,phone_number',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'loyalty_points' => 'nullable|integer|min:0',
        ];
    }
}
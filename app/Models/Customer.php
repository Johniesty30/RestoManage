<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'loyalty_points',
    ];

    protected $casts = [
        'loyalty_points' => 'integer',
        'email_verified_at' => 'datetime', 
    ];
}
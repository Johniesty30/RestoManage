<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'name',
        'phone_number',
        'hire_date',
        'role_name',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];
}
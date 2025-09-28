<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'user_id',
        'role_id',
        'phone_number',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'staff_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'staff_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
        'loyalty_points',
        'position',
        'hire_date',
        'salary',
        'schedule',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'salary' => 'decimal:2',
        'schedule' => 'array',
        'hire_date' => 'date',
    ];

    // Relationships
    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'customer_id');
    }

    public function staffOrders()
    {
        return $this->hasMany(Order::class, 'staff_id');
    }

    // Role Check Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isChef()
    {
        return $this->role === 'chef';
    }

    public function isWaiter()
    {
        return $this->role === 'waiter';
    }

    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isStaff()
    {
        return in_array($this->role, ['admin', 'manager', 'chef', 'waiter', 'cashier']);
    }

    // Scope for filtering by role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeStaff($query)
    {
        return $query->whereIn('role', ['admin', 'manager', 'chef', 'waiter', 'cashier']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

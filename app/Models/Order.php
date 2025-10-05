<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'staff_id',
        'table_id',
        'order_number',
        'total_amount',
        'status',
        'order_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // TAMBAHKAN PROPERTI DI BAWAH INI
    protected $casts = [
        'order_time' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
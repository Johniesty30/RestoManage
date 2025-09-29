<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Methods
    public function calculateSubtotal()
    {
        return $this->unit_price * $this->quantity;
    }

    public function updateSubtotal()
    {
        $this->subtotal = $this->calculateSubtotal();
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            if (empty($orderItem->unit_price)) {
                $orderItem->unit_price = $orderItem->menuItem->price;
            }
            if (empty($orderItem->subtotal)) {
                $orderItem->subtotal = $orderItem->unit_price * $orderItem->quantity;
            }
        });

        static::updating(function ($orderItem) {
            $orderItem->subtotal = $orderItem->unit_price * $orderItem->quantity;
        });
    }
}

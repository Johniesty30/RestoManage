<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'table_id',
        'order_type',
        'status',
        'total_amount',
        'order_time',
        'special_instructions',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'order_time' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('order_time', today());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDineIn($query)
    {
        return $query->where('order_type', 'dine-in');
    }

    public function scopeTakeaway($query)
    {
        return $query->where('order_type', 'takeaway');
    }

    // Methods
    public function calculateTotal()
    {
        return $this->orderItems->sum('subtotal');
    }

    public function updateTotal()
    {
        $this->total_amount = $this->calculateTotal();
        $this->save();
    }

    public function canBeModified()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function markAsPreparing()
    {
        $this->status = 'preparing';
        $this->save();
    }

    public function markAsReady()
    {
        $this->status = 'ready';
        $this->save();
    }

    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->save();
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current_stock',
        'min_stock',
        'unit_of_measure',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
    ];

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= min_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    // Methods
    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock;
    }

    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    public function addStock($quantity)
    {
        $this->current_stock += $quantity;
        $this->save();
    }

    public function reduceStock($quantity)
    {
        if ($this->current_stock >= $quantity) {
            $this->current_stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    public function getStockStatus()
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }
}

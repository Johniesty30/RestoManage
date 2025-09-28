<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'inventory_item_id';
    protected $fillable = ['name', 'quantity_in_stock', 'unit_of_measure', 'reorder_level'];

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients', 'inventory_item_id', 'menu_item_id')
                    ->withPivot('quantity_used');
    }
}

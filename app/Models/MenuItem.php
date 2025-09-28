<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    protected $fillable = ['name', 'description', 'price', 'category_id', 'is_available'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(InventoryItem::class, 'menu_item_ingredients', 'menu_item_id', 'inventory_item_id')
                    ->withPivot('quantity_used');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }
}

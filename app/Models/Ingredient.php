<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $primaryKey = 'ingredient_id';

    protected $fillable = [
        'name',
        'stock_quantity',
        'unit',
        'reorder_level',
    ];

    protected $casts = [
        'stock_quantity' => 'decimal:2',
        'reorder_level' => 'decimal:2',
    ];

    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(
            MenuItem::class,
            'ingredient_menu_item',
            'ingredient_id',
            'item_id'
        );
    }
}
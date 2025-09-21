<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MenuItemIngredient extends Pivot
{
    protected $table = 'menu_item_ingredients';

    protected $primaryKey = ['menu_item_id', 'ingredient_id'];

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'quantity_needed' => 'decimal:2',
    ];
}
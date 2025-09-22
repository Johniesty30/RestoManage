<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Pastikan ini ada

class Ingredient extends Model
{
    use HasFactory;

    protected $primaryKey = 'ingredient_id';

    // PASTIKAN FUNGSI DI BAWAH INI ADA DI DALAM CLASS
    /**
     * The menu items that belong to the Ingredient.
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(
            MenuItem::class,
            'menu_item_ingredients',
            'ingredient_id',
            'item_id'
        );
    }
}
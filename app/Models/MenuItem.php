<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Pastikan ini ada

class MenuItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';

    // PASTIKAN FUNGSI DI BAWAH INI ADA DI DALAM CLASS
    /**
     * The ingredients that belong to the MenuItem.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(
            Ingredient::class,
            'menu_item_ingredients',
            'item_id',
            'ingredient_id'
        )->withPivot('quantity_required');
    }
}
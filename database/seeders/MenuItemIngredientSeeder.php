<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Ingredient;

class MenuItemIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua ID dari menu item dan ingredient
        $menuItems = MenuItem::all();
        $ingredientIds = Ingredient::pluck('ingredient_id');

        // Untuk setiap menu item, lampirkan beberapa ingredient secara acak
        $menuItems->each(function ($menuItem) use ($ingredientIds) {
            $menuItem->ingredients()->attach(
                $ingredientIds->random(rand(2, 5))->toArray(),
                // PERBAIKAN: Menggunakan helper fake()
                ['quantity_required' => fake()->randomFloat(2, 0.1, 0.5)] 
            );
        });
    }
}
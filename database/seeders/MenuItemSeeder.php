<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = MenuCategory::pluck('category_id');

        foreach ($categoryIds as $categoryId) {
            MenuItem::factory()->count(5)->create([
                'category_id' => $categoryId,
            ]);
        }
    }
}
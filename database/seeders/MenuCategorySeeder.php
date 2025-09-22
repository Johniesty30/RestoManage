<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategory;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Appetizers', 'description' => 'Light starters to whet your appetite.'],
            ['name' => 'Main Courses', 'description' => 'Hearty and satisfying main dishes.'],
            ['name' => 'Desserts', 'description' => 'Sweet treats to end your meal.'],
            ['name' => 'Beverages', 'description' => 'Refreshing drinks and beverages.'],
        ];

        foreach ($categories as $category) {
            MenuCategory::create($category);
        }
    }
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MenuCategory;

class MenuItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => MenuCategory::inRandomOrder()->first()->category_id ?? MenuCategory::factory(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
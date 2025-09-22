<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'unit' => $this->faker->randomElement(['kg', 'g', 'l', 'ml', 'pcs']),
            'stock' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
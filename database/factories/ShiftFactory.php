<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Staff;

class ShiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'staff_id' => Staff::factory(),
            'start_time' => $this->faker->dateTimeThisMonth(),
            'end_time' => $this->faker->dateTimeThisMonth('+2 hours'),
        ];
    }
}
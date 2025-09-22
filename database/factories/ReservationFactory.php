<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Table;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->customer_id ?? Customer::factory(),
            'table_id' => Table::inRandomOrder()->first()->table_id ?? Table::factory(),
            'reservation_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'party_size' => $this->faker->numberBetween(1, 8),
        ];
    }
}
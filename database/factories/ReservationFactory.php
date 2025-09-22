<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->customer_id,
            'table_id' => Table::inRandomOrder()->first()->table_id,
            'reservation_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'party_size' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['confirmed', 'pending', 'cancelled']),
        ];
    }
}
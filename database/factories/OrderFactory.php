<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Table;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->customer_id ?? Customer::factory(),
            'staff_id' => Staff::inRandomOrder()->first()->staff_id ?? Staff::factory(),
            'table_id' => Table::inRandomOrder()->first()->table_id ?? Table::factory(),
            'order_time' => $this->faker->dateTimeThisMonth(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'total_price' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
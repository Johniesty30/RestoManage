<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Table;
use App\Models\MenuItem;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerIds = Customer::pluck('customer_id');
        $staffIds = Staff::pluck('staff_id');
        $tableIds = Table::pluck('table_id');
        $menuItemIds = MenuItem::pluck('item_id');

        Order::factory()
            ->count(50)
            ->create()
            ->each(function ($order) use ($menuItemIds) {
                $order->menuItems()->attach(
                    $menuItemIds->random(rand(1, 5))->toArray(),
                    [
                        'quantity' => rand(1, 3),
                        'price' => rand(100, 1000)
                    ]
                );
            });
    }
}
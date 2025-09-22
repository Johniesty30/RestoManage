<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Table;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerIds = Customer::pluck('customer_id');
        $tableIds = Table::pluck('table_id');

        foreach ($customerIds as $customerId) {
            Reservation::factory()->count(2)->create([
                'customer_id' => $customerId,
                'table_id' => $tableIds->random(),
            ]);
        }

        Reservation::factory()->count(20)->create();
    }
}
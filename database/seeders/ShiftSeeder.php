<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\Staff;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffIds = Staff::pluck('staff_id');

        foreach ($staffIds as $staffId) {
            Shift::factory()->count(5)->create([
                'staff_id' => $staffId,
            ]);
        }
    }
}
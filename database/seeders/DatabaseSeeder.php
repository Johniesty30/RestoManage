<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class, 
            CustomerSeeder::class,
            StaffSeeder::class,
            ShiftSeeder::class,
            MenuCategorySeeder::class,
            MenuItemSeeder::class,
            IngredientSeeder::class,
            MenuItemIngredientSeeder::class, 
            TableSeeder::class,
            ReservationSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
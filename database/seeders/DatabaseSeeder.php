<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Category;
use App\Models\Table;
use App\Models\InventoryItem;
use App\Models\MenuItem;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Roles
        $roles = [
            ['role_name' => 'Manager', 'description' => 'Manage entire restaurant'],
            ['role_name' => 'Chef', 'description' => 'Prepare food'],
            ['role_name' => 'Waiter', 'description' => 'Serve customers'],
            ['role_name' => 'Cashier', 'description' => 'Handle payments'],
            ['role_name' => 'Admin', 'description' => 'System administrator'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // ==================== SEED USERS & STAFF ====================

        // Seed Admin User
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '1234567890',
            'is_active' => true,
        ]);

        $adminRole = Role::where('role_name', 'Admin')->first();
        Staff::create([
            'user_id' => $adminUser->id,
            'role_id' => $adminRole->role_id,
            'phone_number' => '1234567890',
            'is_active' => true,
        ]);

        // Seed Manager User
        $managerUser = User::create([
            'name' => 'Manager User',
            'email' => 'manager@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone_number' => '1234567891',
            'is_active' => true,
        ]);

        $managerRole = Role::where('role_name', 'Manager')->first();
        Staff::create([
            'user_id' => $managerUser->id,
            'role_id' => $managerRole->role_id,
            'phone_number' => '1234567891',
            'is_active' => true,
        ]);

        // Seed Chef User
        $chefUser = User::create([
            'name' => 'Chef User',
            'email' => 'chef@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'chef',
            'phone_number' => '1234567892',
            'is_active' => true,
        ]);

        $chefRole = Role::where('role_name', 'Chef')->first();
        Staff::create([
            'user_id' => $chefUser->id,
            'role_id' => $chefRole->role_id,
            'phone_number' => '1234567892',
            'is_active' => true,
        ]);

        // Seed Waiter User
        $waiterUser = User::create([
            'name' => 'Waiter User',
            'email' => 'waiter@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
            'phone_number' => '1234567893',
            'is_active' => true,
        ]);

        $waiterRole = Role::where('role_name', 'Waiter')->first();
        Staff::create([
            'user_id' => $waiterUser->id,
            'role_id' => $waiterRole->role_id,
            'phone_number' => '1234567893',
            'is_active' => true,
        ]);

        // Seed Cashier User
        $cashierUser = User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'phone_number' => '1234567894',
            'is_active' => true,
        ]);

        $cashierRole = Role::where('role_name', 'Cashier')->first();
        Staff::create([
            'user_id' => $cashierUser->id,
            'role_id' => $cashierRole->role_id,
            'phone_number' => '1234567894',
            'is_active' => true,
        ]);

        // Seed Customer User
        $customerUser = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '1234567895',
            'is_active' => true,
        ]);

        Customer::create([
            'user_id' => $customerUser->id,
            'email' => $customerUser->email, // ✅ ambil email dari tabel users
            'phone_number' => '1234567895',
            'loyalty_points' => 100,
        ]);

        // ==================== SEED CATEGORIES ====================
        $categories = [
            ['name' => 'Appetizers', 'description' => 'Starters and small dishes'],
            ['name' => 'Main Course', 'description' => 'Main dishes'],
            ['name' => 'Desserts', 'description' => 'Sweet treats'],
            ['name' => 'Beverages', 'description' => 'Drinks and beverages'],
            ['name' => 'Salads', 'description' => 'Fresh salads'],
            ['name' => 'Soups', 'description' => 'Hot and cold soups'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // ==================== SEED TABLES ====================
        for ($i = 1; $i <= 12; $i++) {
            Table::create([
                'table_number' => 'T' . $i,
                'capacity' => rand(2, 8),
                'status' => $i <= 8 ? 'Available' : 'Available' // 8 available, 4 reserved for variety
            ]);
        }

        // ==================== SEED INVENTORY ITEMS ====================
        $inventoryItems = [
            ['name' => 'Rice', 'quantity_in_stock' => 50.0, 'unit_of_measure' => 'kg', 'reorder_level' => 10.0],
            ['name' => 'Chicken', 'quantity_in_stock' => 20.0, 'unit_of_measure' => 'kg', 'reorder_level' => 5.0],
            ['name' => 'Beef', 'quantity_in_stock' => 15.0, 'unit_of_measure' => 'kg', 'reorder_level' => 3.0],
            ['name' => 'Vegetables', 'quantity_in_stock' => 25.0, 'unit_of_measure' => 'kg', 'reorder_level' => 5.0],
            ['name' => 'Cooking Oil', 'quantity_in_stock' => 10.0, 'unit_of_measure' => 'liter', 'reorder_level' => 2.0],
            ['name' => 'Flour', 'quantity_in_stock' => 30.0, 'unit_of_measure' => 'kg', 'reorder_level' => 5.0],
            ['name' => 'Sugar', 'quantity_in_stock' => 15.0, 'unit_of_measure' => 'kg', 'reorder_level' => 3.0],
            ['name' => 'Salt', 'quantity_in_stock' => 5.0, 'unit_of_measure' => 'kg', 'reorder_level' => 1.0],
            ['name' => 'Eggs', 'quantity_in_stock' => 100.0, 'unit_of_measure' => 'pieces', 'reorder_level' => 20.0],
            ['name' => 'Milk', 'quantity_in_stock' => 20.0, 'unit_of_measure' => 'liter', 'reorder_level' => 5.0],
        ];

        foreach ($inventoryItems as $item) {
            InventoryItem::create($item);
        }

        // ==================== SEED MENU ITEMS ====================
        $appetizers = Category::where('name', 'Appetizers')->first();
        $mainCourse = Category::where('name', 'Main Course')->first();
        $desserts = Category::where('name', 'Desserts')->first();
        $beverages = Category::where('name', 'Beverages')->first();

        $menuItems = [
            [
                'name' => 'Spring Rolls',
                'description' => 'Crispy vegetable spring rolls with sweet chili sauce',
                'price' => 35000,
                'category_id' => $appetizers->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Chicken Satay',
                'description' => 'Grilled chicken skewers with peanut sauce',
                'price' => 45000,
                'category_id' => $appetizers->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Chicken Fried Rice',
                'description' => 'Delicious fried rice with chicken and vegetables',
                'price' => 55000,
                'category_id' => $mainCourse->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Beef Burger',
                'description' => 'Juicy beef burger with cheese and vegetables',
                'price' => 75000,
                'category_id' => $mainCourse->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Grilled Salmon',
                'description' => 'Fresh salmon grilled to perfection with herbs',
                'price' => 120000,
                'category_id' => $mainCourse->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Chocolate Cake',
                'description' => 'Rich chocolate cake with vanilla ice cream',
                'price' => 45000,
                'category_id' => $desserts->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Fruit Salad',
                'description' => 'Fresh seasonal fruits with honey yogurt',
                'price' => 35000,
                'category_id' => $desserts->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Orange Juice',
                'description' => 'Freshly squeezed orange juice',
                'price' => 25000,
                'category_id' => $beverages->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Iced Coffee',
                'description' => 'Chilled coffee with milk and sugar',
                'price' => 30000,
                'category_id' => $beverages->category_id,
                'is_available' => true
            ],
            [
                'name' => 'Green Tea',
                'description' => 'Hot traditional green tea',
                'price' => 20000,
                'category_id' => $beverages->category_id,
                'is_available' => true
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }

        // ==================== SEED SAMPLE ORDERS ====================
        $customer = Customer::first();
        $waiter = Staff::whereHas('user', function($query) {
            $query->where('role', 'waiter');
        })->first();

        if ($customer && $waiter) {
            $order = \App\Models\Order::create([
                'customer_id' => $customer->customer_id,
                'table_id' => 1,
                'staff_id' => $waiter->staff_id,
                'order_type' => 'Dine-In',
                'total_amount' => 130000,
                'status' => 'Completed'
            ]);

            $chickenRice = MenuItem::where('name', 'Chicken Fried Rice')->first();
            $orangeJuice = MenuItem::where('name', 'Orange Juice')->first();

            if ($chickenRice && $orangeJuice) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->order_id,
                    'item_id' => $chickenRice->item_id,
                    'quantity' => 2,
                    'subtotal' => 110000
                ]);

                \App\Models\OrderItem::create([
                    'order_id' => $order->order_id,
                    'item_id' => $orangeJuice->item_id,
                    'quantity' => 1,
                    'subtotal' => 25000
                ]);
            }
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👥 Default login credentials:');
        $this->command->info('   Admin: admin@restaurant.com / password');
        $this->command->info('   Manager: manager@restaurant.com / password');
        $this->command->info('   Chef: chef@restaurant.com / password');
        $this->command->info('   Waiter: waiter@restaurant.com / password');
        $this->command->info('   Cashier: cashier@restaurant.com / password');
        $this->command->info('   Customer: customer@example.com / password');
    }
}

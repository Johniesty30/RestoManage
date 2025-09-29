<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'chef', 'waiter', 'cashier', 'customer'])->default('customer');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('loyalty_points')->default(0);
            $table->string('position')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->json('schedule')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'is_active',
                'loyalty_points',
                'position',
                'hire_date',
                'salary',
                'schedule'
            ]);
        });
    }
};

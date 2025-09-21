<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('customer_id')->nullable()->constrained('customers', 'customer_id')->onDelete('set null');
            $table->foreignId('staff_id')->constrained('staff', 'staff_id')->onDelete('restrict');
            $table->foreignId('table_id')->nullable()->constrained('tables', 'table_id')->onDelete('set null');
            $table->string('order_type', 20)->default('Dine-in'); // 'Dine-in' or 'Takeaway'
            $table->string('order_status', 20)->default('Pending'); // 'Pending', 'Preparing', 'Served', 'Paid'
            $table->timestamp('order_time')->useCurrent();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

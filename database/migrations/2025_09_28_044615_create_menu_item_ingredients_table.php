<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_item_ingredients', function (Blueprint $table) {
            $table->foreignId('menu_item_id')->constrained('menu_items', 'item_id');
            $table->foreignId('inventory_item_id')->constrained('inventory_items', 'inventory_item_id');
            $table->decimal('quantity_used', 10, 2);
            $table->primary(['menu_item_id', 'inventory_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_ingredients');
    }
};

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
        Schema::create('menu_item_ingredients', function (Blueprint $table) {
            $table->foreignId('menu_item_id')->constrained('menu_items', 'item_id')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients', 'ingredient_id')->onDelete('cascade');
            $table->decimal('quantity_needed', 10, 2);
            $table->primary(['menu_item_id', 'ingredient_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_ingredients');
    }
};

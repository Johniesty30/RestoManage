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
            // Hapus $table->id(); jika ada
            $table->foreignId('item_id')->constrained('menu_items', 'item_id')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients', 'ingredient_id')->cascadeOnDelete();
            $table->decimal('quantity_required', 8, 2);
            $table->primary(['item_id', 'ingredient_id']); // Menjadikan kedua kolom sebagai primary key
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
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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_category');
            $table->string('type');
            $table->string('quantity_unit');
            $table->integer('total_stock')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->enum('status', ['Available', 'Low Stock', 'Out of Stock'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

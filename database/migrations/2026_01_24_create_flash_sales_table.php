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
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('sale_price', 10, 2); // Discounted price
            $table->integer('quantity_limit')->nullable(); // Max quantity available at sale price
            $table->integer('quantity_sold')->default(0); // Track sold quantity
            $table->dateTime('starts_at'); // When flash sale begins
            $table->dateTime('ends_at'); // When flash sale ends
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Composite unique: one active flash sale per product at a time
            $table->unique(['product_id', 'starts_at', 'ends_at']);

            // Indexes
            $table->index('product_id');
            $table->index('is_active');
            $table->index(['starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};

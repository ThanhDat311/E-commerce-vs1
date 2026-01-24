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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., "SAVE10", "SUMMER2026"
            $table->enum('type', ['percent', 'fixed']); // Percentage or fixed amount
            $table->decimal('value', 10, 2); // Discount value (e.g., 10 for 10% or $10)
            $table->decimal('min_order', 10, 2)->nullable(); // Minimum order amount
            $table->integer('max_usage')->nullable(); // Maximum times coupon can be used
            $table->integer('used_count')->default(0); // Track how many times used
            $table->integer('per_user_limit')->nullable(); // Limit per user (e.g., max 1 use per user)
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->softDeletes(); // Soft delete support
            $table->timestamps();

            // Indexes for performance
            $table->index('code');
            $table->index('is_active');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

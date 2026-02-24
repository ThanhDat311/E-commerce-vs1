<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deal_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('special_price', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['deal_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal_products');
    }
};

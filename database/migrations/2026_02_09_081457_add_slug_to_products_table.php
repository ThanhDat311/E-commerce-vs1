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
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();
        });

        // Update existing products
        \Illuminate\Support\Facades\DB::table('products')->get()->each(function ($product) {
            $slug = \Illuminate\Support\Str::slug($product->name);
            // Ensure uniqueness simply for existing data if needed, but for now just name
            // If ID is needed: . '-' . $product->id
            \Illuminate\Support\Facades\DB::table('products')
                ->where('id', $product->id)
                ->update(['slug' => $slug.'-'.$product->id]);
        });

        // Make column unique and not null
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

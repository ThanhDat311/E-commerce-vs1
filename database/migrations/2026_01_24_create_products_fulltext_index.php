<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds full-text index to products table for columns: name, description
     * Improves performance of full-text search queries in MySQL
     */
    public function up(): void
    {
        // Check if running MySQL/MariaDB (not SQLite)
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('products', function (Blueprint $table) {
                // Add full-text index for name and description
                $table->fullText(['name', 'description'])->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('products', function (Blueprint $table) {
                // Drop full-text index
                $table->dropFullText(['name', 'description']);
            });
        }
    }
};

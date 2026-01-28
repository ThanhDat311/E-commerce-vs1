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
        Schema::table('price_suggestions', function (Blueprint $table) {
            $table->decimal('confidence', 3, 2)->nullable()->default(0.5)->after('new_price');
            $table->text('reason')->nullable()->after('confidence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_suggestions', function (Blueprint $table) {
            $table->dropColumn('confidence');
            $table->dropColumn('reason');
        });
    }
};

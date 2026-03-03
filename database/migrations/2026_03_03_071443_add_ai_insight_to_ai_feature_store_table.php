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
        Schema::table('ai_feature_store', function (Blueprint $table) {
            $table->text('ai_insight')->nullable()->after('reasons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_feature_store', function (Blueprint $table) {
            $table->dropColumn('ai_insight');
        });
    }
};

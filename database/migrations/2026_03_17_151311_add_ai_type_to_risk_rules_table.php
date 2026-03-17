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
        Schema::table('risk_rules', function (Blueprint $table) {
            $table->string('ai_type', 20)->default('transaction')->after('rule_key');
            $table->index('ai_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_rules', function (Blueprint $table) {
            $table->dropIndex(['ai_type']);
            $table->dropColumn('ai_type');
        });
    }
};

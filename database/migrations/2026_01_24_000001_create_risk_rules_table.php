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
        // Drop old table if it exists with old structure
        if (Schema::hasTable('risk_rules')) {
            $columns = Schema::getColumnListing('risk_rules');
            if (in_array('rule_name', $columns)) {
                Schema::drop('risk_rules');
            } else {
                // Table already has new structure, skip creation
                return;
            }
        }

        Schema::create('risk_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_key')->unique(); // e.g., 'guest_checkout', 'new_user_24h'
            $table->integer('weight')->default(0); // Risk weight (0-100)
            $table->text('description'); // Detailed description
            $table->boolean('is_active')->default(true); // Enable/disable rule
            $table->timestamps();

            $table->index('rule_key');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_rules');
    }
};

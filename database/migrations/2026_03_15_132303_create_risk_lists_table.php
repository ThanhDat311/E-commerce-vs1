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
        Schema::create('risk_lists', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'ip' or 'user_id'
            $table->string('value'); // 192.168.1.1 or user ID 5
            $table->string('action'); // 'block' or 'whitelist'
            $table->text('reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Prevent duplicate active rules for same type & value
            $table->unique(['type', 'value', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_lists');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->foreignId('vendor_id')->after('id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->after('vendor_id');
            $table->decimal('commission_rate', 5, 2)->default(10.00)->after('amount');
            $table->decimal('commission_amount', 15, 2)->after('commission_rate');
            $table->string('status')->default('pending')->after('commission_amount'); // pending, paid
            $table->date('period_start')->after('status');
            $table->date('period_end')->after('period_start');
            $table->text('notes')->nullable()->after('period_end');

            $table->index(['vendor_id', 'status']);
            $table->index(['period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn([
                'vendor_id',
                'amount',
                'commission_rate',
                'commission_amount',
                'status',
                'period_start',
                'period_end',
                'notes',
            ]);
        });
    }
};

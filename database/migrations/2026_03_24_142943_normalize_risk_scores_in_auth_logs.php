<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalize risk_score values in auth_logs that were incorrectly stored
     * on a 0–100 scale instead of the correct 0.0–1.0 scale.
     *
     * Any record with risk_score > 1.0 is divided by 100.
     * risk_level and auth_decision are recalculated to stay consistent.
     */
    public function up(): void
    {
        DB::statement("
            UPDATE auth_logs
            SET
                risk_score    = risk_score / 100.0,
                risk_level    = CASE
                    WHEN (risk_score / 100.0) >= 0.80 THEN 'critical'
                    WHEN (risk_score / 100.0) >= 0.60 THEN 'high'
                    WHEN (risk_score / 100.0) >= 0.30 THEN 'medium'
                    ELSE 'low'
                END,
                auth_decision = CASE
                    WHEN (risk_score / 100.0) >= 0.80 THEN 'block_access'
                    WHEN (risk_score / 100.0) >= 0.60 THEN 'challenge_biometric'
                    WHEN (risk_score / 100.0) >= 0.30 THEN 'challenge_otp'
                    ELSE 'passive_auth_allow'
                END
            WHERE risk_score > 1.0
        ");
    }

    /**
     * This migration is a one-time data fix and cannot be cleanly reversed
     * because we don't know the exact original integer values.
     */
    public function down(): void
    {
        // Irreversible data fix – no rollback possible.
    }
};

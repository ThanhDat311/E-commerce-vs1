<?php

namespace App\Jobs;

use App\Models\PriceSuggestion;
use App\Models\Setting;
use App\Services\PricingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AutoApplyPriceSuggestions implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PricingService $pricingService): void
    {
        // Check if auto-apply is enabled in settings
        $isAutoApplyEnabled = Setting::get('auto_apply_price_suggestions', 'ai', false);

        if (! $isAutoApplyEnabled) {
            Log::info('[AutoApplyPriceSuggestions] Auto-apply is disabled. Skipping.');

            return;
        }

        // Retrieve all pending suggestions
        $pendingSuggestions = PriceSuggestion::where('status', 'pending')->get();
        $approvedCount = 0;

        foreach ($pendingSuggestions as $suggestion) {
            try {
                $pricingService->approveSuggestion($suggestion);
                $approvedCount++;
            } catch (\Exception $e) {
                Log::error('[AutoApplyPriceSuggestions] Failed to auto-approve suggestion ID '.$suggestion->id.': '.$e->getMessage());
            }
        }

        if ($approvedCount > 0) {
            Log::info("[AutoApplyPriceSuggestions] Successfully auto-approved {$approvedCount} pending price suggestions.");
        }
    }
}

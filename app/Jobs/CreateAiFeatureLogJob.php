<?php

namespace App\Jobs;

use App\Models\AiFeatureStore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateAiFeatureLogJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $featureData
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            AiFeatureStore::create($this->featureData);
        } catch (\Exception $e) {
            Log::error("Failed to store AI features in job: " . $e->getMessage());
        }
    }
}

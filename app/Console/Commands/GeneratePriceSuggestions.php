<?php

namespace App\Console\Commands;

use App\Services\PricingService;
use Illuminate\Console\Command;

class GeneratePriceSuggestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pricing:generate-suggestions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dynamic price suggestions using AI Decision Engine';

    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        parent::__construct();
        $this->pricingService = $pricingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating price suggestions...');

        $this->pricingService->generateSuggestions();

        $this->info('Price suggestions generated successfully.');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class IndexProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:index-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index all products in Scout full-text search database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting product indexing...');

        try {
            $products = Product::query()->get();
            $total = $products->count();

            $this->info("Indexing {$total} products...");

            // Index products using Scout's searchable method
            Product::query()->searchable();

            $this->info("✓ Successfully indexed {$total} products");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to index products: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

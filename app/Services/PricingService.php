<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Models\PriceSuggestion;
use App\Models\Product;

class PricingService
{
    protected $productRepository;
    protected $aiEngine;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        AIDecisionEngine $aiEngine
    ) {
        $this->productRepository = $productRepository;
        $this->aiEngine = $aiEngine;
    }

    /**
     * Generate price suggestions for all products
     */
    public function generateSuggestions()
    {
        $products = $this->productRepository->all();

        foreach ($products as $product) {
            $this->generateSuggestionForProduct($product);
        }
    }

    /**
     * Generate price suggestion for a single product
     */
    public function generateSuggestionForProduct(Product $product)
    {
        // Skip if already has pending suggestion
        if (PriceSuggestion::where('product_id', $product->id)->where('status', 'pending')->exists()) {
            return;
        }

        $productData = [
            'price' => $product->price,
            'cost_price' => $product->cost_price ?? 0,
            'stock_quantity' => $product->stock_quantity ?? 0,
        ];

        // For now, use empty market data - can be enhanced later
        $marketData = [];

        $suggestion = $this->aiEngine->suggestDynamicPrice($productData, $marketData);

        $suggestedPrice = $suggestion['decision'];

        // Only create suggestion if price changed
        if (abs($suggestedPrice - $product->price) > 0.01) {
            PriceSuggestion::create([
                'product_id' => $product->id,
                'old_price' => $product->price,
                'new_price' => $suggestedPrice,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Approve a price suggestion
     */
    public function approveSuggestion(PriceSuggestion $suggestion)
    {
        if ($suggestion->status !== 'pending') {
            throw new \Exception('Suggestion is not pending');
        }

        // Update product price
        $product = $suggestion->product;
        $product->price = $suggestion->new_price;
        $product->save();

        // Update suggestion status
        $suggestion->status = 'approved';
        $suggestion->save();
    }

    /**
     * Reject a price suggestion
     */
    public function rejectSuggestion(PriceSuggestion $suggestion)
    {
        if ($suggestion->status !== 'pending') {
            throw new \Exception('Suggestion is not pending');
        }

        $suggestion->status = 'rejected';
        $suggestion->save();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;

class RecommendationController extends Controller
{
    public function __construct(
        protected RecommendationService $recommendationService
    ) {}

    /**
     * Get recommendations for a specific product.
     */
    public function show(Product $product): JsonResponse
    {
        $recommendations = [
            'purchased_together' => $this->recommendationService->purchasedTogether($product),
            'category_based' => $this->recommendationService->categoryBased($product),
            'trending' => $this->recommendationService->trending(),
        ];

        return response()->json($recommendations);
    }
}

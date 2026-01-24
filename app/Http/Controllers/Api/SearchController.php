<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * SearchController
 * 
 * Handles all search-related API endpoints for product discovery
 */
class SearchController extends Controller
{
    protected $searchService;

    public function __construct(ProductSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Search products with advanced filtering
     * 
     * GET /api/v1/search
     * 
     * Query Parameters:
     * - q (string): Search query (name, description, SKU)
     * - category (integer): Filter by category ID
     * - min_price (decimal): Minimum price filter
     * - max_price (decimal): Maximum price filter
     * - sort (string): Sort option (price_asc, price_desc, newest)
     * - per_page (integer): Results per page (default: 6)
     * 
     * Example: /api/v1/search?q=laptop&category=1&min_price=100&max_price=2000&sort=price_asc
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
            'category' => 'nullable|integer|exists:categories,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort' => 'nullable|in:price_asc,price_desc,newest',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = $validated['q'] ?? null;
        $perPage = $validated['per_page'] ?? 6;

        $filters = [
            'category' => $validated['category'] ?? null,
            'min_price' => $validated['min_price'] ?? null,
            'max_price' => $validated['max_price'] ?? null,
            'sort' => $validated['sort'] ?? null,
        ];

        // Execute advanced search
        $results = $this->searchService->search($query, $filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'current_page' => $results->currentPage(),
                'per_page' => $results->perPage(),
                'last_page' => $results->lastPage(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem(),
                'has_more' => $results->hasMorePages(),
            ],
            'query' => $query,
            'filters_applied' => array_filter($filters),
        ]);
    }

    /**
     * Get search suggestions for autocomplete
     * 
     * GET /api/v1/search/suggestions
     * 
     * Query Parameters:
     * - q (string, required): Search query (minimum 2 characters)
     * - limit (integer): Maximum suggestions (default: 10)
     * 
     * Example: /api/v1/search/suggestions?q=lap&limit=5
     * 
     * Response:
     * [
     *     {
     *         "id": 1,
     *         "name": "Laptop Dell XPS",
     *         "price": "999.99",
     *         "image": "url_to_image",
     *         "category": "Electronics"
     *     }
     * ]
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function suggestions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:255',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = $validated['q'];
        $limit = $validated['limit'] ?? 10;

        $suggestions = $this->searchService->getSuggestions($query, $limit);

        return response()->json([
            'status' => 'success',
            'data' => $suggestions,
            'query' => $query,
            'count' => count($suggestions),
        ]);
    }

    /**
     * Re-index all products (Admin only)
     * 
     * POST /api/v1/search/reindex
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function reindex(Request $request): JsonResponse
    {
        // Authorization check (admin only)
        if (!auth()->check() || !auth()->user()->isAdmin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $this->searchService->indexAll();

            return response()->json([
                'status' => 'success',
                'message' => 'All products reindexed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reindex products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

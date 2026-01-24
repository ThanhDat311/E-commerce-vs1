<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Builder;

/**
 * ProductSearchService
 * 
 * Advanced search service that uses Laravel Scout for full-text search
 * with fallback mechanism to SQL LIKE search if Scout fails or returns no results
 */
class ProductSearchService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Search products with advanced filtering
     * 
     * Attempts Scout full-text search first, then falls back to SQL LIKE search if needed
     * 
     * @param string|null $query Search query string
     * @param array $filters Filtering options (category, min_price, max_price)
     * @param int $perPage Pagination limit
     * @return \Illuminate\Pagination\Paginator
     */
    public function search(?string $query = null, array $filters = [], int $perPage = 6)
    {
        try {
            // Try Scout full-text search first
            if (!empty($query)) {
                $results = $this->searchWithScout($query, $filters, $perPage);

                // If Scout returns results, use them
                if ($results->count() > 0 || $results->total() > 0) {
                    return $results;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Scout search failed, falling back to SQL search', [
                'error' => $e->getMessage(),
                'query' => $query
            ]);
        }

        // Fallback to standard SQL search
        return $this->searchWithSQL($query, $filters, $perPage);
    }

    /**
     * Search using Laravel Scout full-text search
     * 
     * @param string $query Search query
     * @param array $filters Filtering options
     * @param int $perPage Pagination limit
     * @return \Illuminate\Pagination\Paginator
     */
    protected function searchWithScout(string $query, array $filters, int $perPage)
    {
        $builder = Product::search($query);

        // Apply category filter
        if (!empty($filters['category'])) {
            $builder->where('category_id', $filters['category']);
        }

        // Apply price range filters
        if (!empty($filters['min_price'])) {
            $builder->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $builder->where('price', '<=', $filters['max_price']);
        }

        // Apply sorting
        $builder = $this->applySorting($builder, $filters['sort'] ?? null);

        return $builder->paginate($perPage)->withQueryString();
    }

    /**
     * Fallback SQL LIKE search
     * 
     * @param string|null $query Search query
     * @param array $filters Filtering options
     * @param int $perPage Pagination limit
     * @return \Illuminate\Pagination\Paginator
     */
    protected function searchWithSQL(?string $query, array $filters, int $perPage)
    {
        $queryBuilder = Product::query();

        // Search in name and description using LIKE
        if (!empty($query)) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%");
            });
        }

        // Apply category filter
        if (!empty($filters['category'])) {
            $queryBuilder->where('category_id', $filters['category']);
        }

        // Apply price range filters
        if (!empty($filters['min_price'])) {
            $queryBuilder->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $queryBuilder->where('price', '<=', $filters['max_price']);
        }

        // Apply sorting
        $queryBuilder = $this->applySortingSQL($queryBuilder, $filters['sort'] ?? null);

        return $queryBuilder->paginate($perPage)->withQueryString();
    }

    /**
     * Apply sorting for Scout search
     * 
     * @param Builder $builder Scout query builder
     * @param string|null $sortBy Sort parameter
     * @return Builder
     */
    protected function applySorting(?Builder $builder, ?string $sortBy): Builder
    {
        if (!$builder) {
            $builder = Product::search('');
        }

        switch ($sortBy) {
            case 'price_asc':
                return $builder->orderBy('price', 'asc');
            case 'price_desc':
                return $builder->orderBy('price', 'desc');
            case 'newest':
                return $builder->orderBy('created_at', 'desc');
            default:
                return $builder->orderBy('id', 'desc');
        }
    }

    /**
     * Apply sorting for SQL search
     * 
     * @param mixed $queryBuilder Eloquent query builder
     * @param string|null $sortBy Sort parameter
     * @return mixed
     */
    protected function applySortingSQL($queryBuilder, ?string $sortBy)
    {
        switch ($sortBy) {
            case 'price_asc':
                return $queryBuilder->orderBy('price', 'asc');
            case 'price_desc':
                return $queryBuilder->orderBy('price', 'desc');
            case 'newest':
                return $queryBuilder->orderBy('created_at', 'desc');
            default:
                return $queryBuilder->orderBy('id', 'desc');
        }
    }

    /**
     * Get search suggestions for autocomplete
     * 
     * Returns a list of product names and descriptions for autocomplete functionality
     * 
     * @param string $query Search query (minimum 2 characters)
     * @param int $limit Maximum results to return
     * @return array
     */
    public function getSuggestions(string $query, int $limit = 10): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        try {
            // Try Scout first for better relevance
            $scoutResults = Product::search($query)
                ->limit($limit)
                ->get();

            if ($scoutResults->isNotEmpty()) {
                return $scoutResults->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => $product->image_url,
                        'category' => $product->category?->name,
                    ];
                })->toArray();
            }
        } catch (\Exception $e) {
            Log::warning('Scout suggestions failed, using SQL fallback', [
                'error' => $e->getMessage(),
                'query' => $query
            ]);
        }

        // Fallback to SQL LIKE search
        return DB::table('products')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'price', 'image_url', 'category_id'])
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image_url,
                    'category' => \App\Models\Category::find($product->category_id)?->name,
                ];
            })
            ->toArray();
    }

    /**
     * Index all products in Scout
     * 
     * @return void
     */
    public function indexAll(): void
    {
        try {
            Product::query()->searchable();
            Log::info('All products indexed successfully in Scout');
        } catch (\Exception $e) {
            Log::error('Failed to index products', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Flush all Scout indices
     * 
     * @return void
     */
    public function flush(): void
    {
        try {
            Product::query()->unsearchable();
            Log::info('Scout index flushed successfully');
        } catch (\Exception $e) {
            Log::error('Failed to flush Scout index', ['error' => $e->getMessage()]);
        }
    }
}

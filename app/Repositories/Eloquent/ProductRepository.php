<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    /**
     * Cache key for home page products
     */
    const CACHE_KEY_HOME_PRODUCTS = 'home_products';

    /**
     * Cache TTL in minutes (60 minutes = 3600 seconds)
     */
    const CACHE_TTL = 3600;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findByIds(array $ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Create a new product and invalidate home page cache
     */
    public function create(array $data)
    {
        $product = Product::create($data);

        // Invalidate cache to reflect changes immediately on frontend
        $this->invalidateHomePageCache();

        return $product;
    }

    /**
     * Update a product and invalidate home page cache
     */
    public function update(int $id, array $data)
    {
        $product = $this->find($id);
        if ($product) {
            $product->update($data);

            // Invalidate cache to reflect changes immediately on frontend
            $this->invalidateHomePageCache();

            return $product;
        }

        return null;
    }

    /**
     * Delete a product and invalidate home page cache
     */
    public function delete(int $id)
    {
        $product = $this->find($id);
        if ($product) {
            $result = $product->delete();

            // Invalidate cache to reflect changes immediately on frontend
            $this->invalidateHomePageCache();

            return $result;
        }

        return false;
    }

    // --- HELPER METHOD FOR CACHE INVALIDATION ---

    /**
     * Invalidate home page products cache
     * Called automatically when products are created, updated, or deleted
     */
    private function invalidateHomePageCache()
    {
        Cache::forget(self::CACHE_KEY_HOME_PRODUCTS);
    }

    // --- IMPLEMENTATION CÁC HÀM CỌC ---

    public function getFilteredProducts(array $filters, int $perPage = 6)
    {
        $query = $this->model->query();

        // 1. Filter by Keyword
        if (! empty($filters['keyword'])) {
            $query->where('name', 'LIKE', "%{$filters['keyword']}%");
        }

        // 2. Filter by Category
        if (! empty($filters['category'])) {
            // Giả sử URL là ?category=1
            $query->where('category_id', $filters['category']);
        }

        // 3. Filter by Price Range (nếu có)
        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // 4. Sorting
        if (! empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getCategoriesWithProductCount()
    {
        // Lấy category và đếm số sản phẩm active
        // Yêu cầu Model Category phải có relation products()
        return Category::withCount('products')->get();
    }

    public function getProductDetails(int $id)
    {
        // Eager load category, reviews và tính average rating ngay lập tức để tránh N+1
        return $this->model->with(['category', 'reviews.user'])
            ->withAvg('ratings', 'rating')
            ->findOrFail($id);
    }

    public function getRelatedProducts($currentProduct, int $limit = 4)
    {
        $minPrice = $currentProduct->price * 0.7;
        $maxPrice = $currentProduct->price * 1.3;

        // Logic tìm kiếm thông minh
        $related = $this->model->where('category_id', $currentProduct->category_id)
            ->where('id', '!=', $currentProduct->id)
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->inRandomOrder()
            ->take($limit)
            ->get();

        // Fallback logic nếu không đủ sản phẩm
        if ($related->count() < $limit) {
            $more = $this->model->where('category_id', $currentProduct->category_id)
                ->where('id', '!=', $currentProduct->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->take($limit - $related->count())
                ->get();

            $related = $related->merge($more);
        }

        return $related;
    }

    /**
     * Get home page products with Redis caching
     *
     * Fetches latest 8 products and new arrivals (is_new = true) or random fallback
     * Results are cached for 60 minutes to improve performance
     * Cache is automatically invalidated when products are created, updated, or deleted
     *
     * @param  int  $limit  Number of products to fetch per category (default: 8)
     * @return array Array containing 'newProducts' and 'arrivals' keys
     */
    public function getHomePageProducts(int $limit = 8)
    {
        return Cache::remember(
            self::CACHE_KEY_HOME_PRODUCTS,
            self::CACHE_TTL,
            function () use ($limit) {
                // Get latest 8 products for Tab 1 (All Products)
                $newProducts = $this->model
                    ->latest()
                    ->take($limit)
                    ->get();

                // Get 8 products marked as new for Tab 2 (New Arrivals)
                $arrivals = $this->model
                    ->where('is_new', true)
                    ->latest()
                    ->take($limit)
                    ->get();

                // If no arrivals marked, use random selection for better UX
                if ($arrivals->isEmpty()) {
                    $arrivals = $this->model
                        ->inRandomOrder()
                        ->take($limit)
                        ->get();
                }

                return [
                    'newProducts' => $newProducts,
                    'arrivals' => $arrivals,
                ];
            }
        );
    }

    public function getLowStockCount(int $threshold = 10)
    {
        return $this->model->where('stock_quantity', '<', $threshold)->count();
    }

    /**
     * Filter and sort products for Shop page
     */
    /**
     * Filter and sort products for Shop page
     */
    public function filterAndSort(array $filters, int $perPage = 12)
    {
        $query = $this->model->query();

        // 1. Filter by Keyword
        if (! empty($filters['search'])) {
            $query->where('name', 'LIKE', "%{$filters['search']}%");
        }

        // 2. Filter by Category (Slug or ID) - Support Array
        if (! empty($filters['category'])) {
            $categories = is_array($filters['category']) ? $filters['category'] : explode(',', $filters['category']);
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories)->orWhereIn('id', $categories);
            });
        }

        // 3. Filter by Price Range
        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // 4. Filter by Brand (Vendor) - Support Array
        if (! empty($filters['brands'])) {
             $brands = is_array($filters['brands']) ? $filters['brands'] : explode(',', $filters['brands']);
             // Assuming 'brands' are vendor IDs for now since we don't have a brand column
             // If $brands contains names, we need to join users table.
             // Let's assume frontend sends Vendor IDs.
             $query->whereIn('vendor_id', $brands);
        }

        // 5. Filter by Rating
        if (! empty($filters['rating'])) {
            $rating = (int) $filters['rating'];
            // This requires a subquery or join to calculate average rating
            // Using withAvg in model, but for filtering we need `having`.
            // Since we are paginating, `having` can be tricky with standard paginate if not careful.
            // A simpler approach for now is whereHas with a basic check if individual ratings exist >= val, 
            // but usually we want AVERAGE >= val.
            // Let's try to filter by products that have at least one rating >= val for MVP or implement avg check properly.
            // Correct way for Average Rating filter:
            $query->withAvg('ratings', 'rating')
                  ->having('ratings_avg_rating', '>=', $rating);
        }

        // 6. Sorting
        if (! empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'rating':
                     $query->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating', 'desc');
                     break;
                default: // latest
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getBrandsWithProductCount()
    {
        // Return Vendors who have products
        // Using 'shop_name' from Vendor profile if available, else User name.
        // Assuming User model has 'name'.
        return \App\Models\User::whereHas('products')
            ->withCount('products')
            ->get(['id', 'name']); // Adjust fields as needed
    }

    public function getPriceRange()
    {
        return [
            'min' => $this->model->min('price') ?? 0,
            'max' => $this->model->max('price') ?? 0,
        ];
    }

    /**
     * Find product by slug with eager loading
     */
    public function findBySlug(string $slug)
    {
        // Assuming 'slug' column exists. If not, we might need to use ID or logic.
        // For now, let's assume we might need to fallback to ID if slug not found or standard lookup
        // But the requirement says "SEO: Bắt buộc dùng slug".
        // Let's implement search by slug column.

        return $this->model->where('slug', $slug)
            ->with(['category', 'reviews.user', 'vendor'])
            ->withAvg('ratings', 'rating')
            ->firstOrFail();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $productRepository) {}

    /**
     * Return a paginated, filterable list of products.
     *
     * Supports query params: category_id, brand, min_price, max_price, sort, search, per_page
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->query('per_page', 12), 50);
        $products = $this->productRepository->filterAndSort($request->all(), $perPage);

        return ProductResource::collection($products);
    }

    /**
     * Return a single product by slug.
     */
    public function show(string $slug): JsonResponse|ProductResource
    {
        $product = $this->productRepository->findBySlug($slug);

        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->loadMissing(['category', 'images', 'vendor']);

        return new ProductResource($product);
    }
}

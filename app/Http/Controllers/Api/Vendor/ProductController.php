<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of vendor's own products.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $vendor = Auth::user();

        $products = Product::where('vendor_id', $vendor->id)
            ->with(['category', 'images'])
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $vendor = Auth::user();
        $data = $request->validated();

        $data['vendor_id'] = $vendor->id;

        if (isset($data['quantity'])) {
            $data['stock_quantity'] = $data['quantity'];
            unset($data['quantity']);
        }

        $data['is_new'] = $request->boolean('is_new');
        $data['is_featured'] = $request->boolean('is_featured');

        $product = Product::create($data);
        $product->load(['category', 'images']);

        return $this->successResponse(new ProductResource($product), 'Product created successfully.', 201);
    }

    /**
     * Display the specified product (owner only).
     */
    public function show(Product $product): JsonResponse
    {
        $this->authorizeOwnership($product);

        $product->load(['category', 'images', 'vendor']);

        return $this->successResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified product (owner only).
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorizeOwnership($product);

        $data = $request->validated();
        $data['vendor_id'] = Auth::id();

        if (isset($data['quantity'])) {
            $data['stock_quantity'] = $data['quantity'];
            unset($data['quantity']);
        }

        $data['is_new'] = $request->boolean('is_new');
        $data['is_featured'] = $request->boolean('is_featured');

        $product->update($data);
        $product->load(['category', 'images']);

        return $this->successResponse(new ProductResource($product), 'Product updated successfully.');
    }

    /**
     * Soft-delete the specified product (owner only).
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorizeOwnership($product);

        $product->delete();

        return $this->successResponse(null, 'Product deleted successfully.');
    }

    /**
     * Ensure the authenticated vendor owns this product.
     */
    private function authorizeOwnership(Product $product): void
    {
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'You are not authorized to manage this product.');
        }
    }
}

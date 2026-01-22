<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::paginate(10);
        return response()->json($products);
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|url',
            'is_new' => 'boolean',
            'is_featured' => 'boolean',
            'description' => 'nullable|string',
        ]);

        // For vendors, automatically set vendor_id
        if (auth()->user()->role_id === 4) {
            $validated['vendor_id'] = auth()->id();
        }

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|url',
            'is_new' => 'boolean',
            'is_featured' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}

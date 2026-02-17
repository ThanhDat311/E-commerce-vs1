<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of vendor's products only
     */
    public function index()
    {
        $vendor = Auth::user();
        $products = Product::where('vendor_id', $vendor->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.vendor.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.vendor.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage
     */
    public function store(StoreProductRequest $request)
    {
        $vendor = Auth::user();
        $data = $request->validated();

        // Automatically assign vendor_id to current user
        $data['vendor_id'] = $vendor->id;

        // Map 'quantity' from form to 'stock_quantity' in database
        if (isset($data['quantity'])) {
            $data['stock_quantity'] = $data['quantity'];
            unset($data['quantity']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            if (!File::exists(public_path('img/products'))) {
                File::makeDirectory(public_path('img/products'), 0755, true);
            }

            $file->move(public_path('img/products'), $filename);
            $data['image_url'] = 'img/products/' . $filename;
        }

        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        Product::create($data);

        return redirect()->route('vendor.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        // Ensure vendor can only edit their own products
        $this->authorize('update', $product);

        $categories = Category::all();
        return view('pages.vendor.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Ensure vendor can only update their own products
        $this->authorize('update', $product);

        $data = $request->validated();
        $vendor = Auth::user();

        // Ensure vendor_id cannot be changed
        $data['vendor_id'] = $vendor->id;

        // Map 'quantity' from form to 'stock_quantity' in database
        if (isset($data['quantity'])) {
            $data['stock_quantity'] = $data['quantity'];
            unset($data['quantity']);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image_url && File::exists(public_path($product->image_url))) {
                File::delete(public_path($product->image_url));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            if (!File::exists(public_path('img/products'))) {
                File::makeDirectory(public_path('img/products'), 0755, true);
            }

            $file->move(public_path('img/products'), $filename);
            $data['image_url'] = 'img/products/' . $filename;
        }

        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product->update($data);

        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy(Product $product)
    {
        // Ensure vendor can only delete their own products
        $this->authorize('delete', $product);

        // Delete image if exists
        if ($product->image_url && File::exists(public_path($product->image_url))) {
            File::delete(public_path($product->image_url));
        }

        // Soft delete - products with orders can now be deleted safely
        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Show the specified product
     */
    public function show(Product $product)
    {
        // Ensure vendor can only view their own products
        $this->authorize('view', $product);

        return view('pages.vendor.products.show', compact('product'));
    }
}

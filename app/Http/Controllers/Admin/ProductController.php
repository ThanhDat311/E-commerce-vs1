<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        return view('pages.admin.products.create', [
            'categories' => $categories,
        ]);
    }

    // [FIX] Sử dụng StoreProductRequest
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Map 'quantity' from form to 'stock_quantity' in database
        if (isset($data['quantity'])) {
            $data['stock_quantity'] = $data['quantity'];
            unset($data['quantity']);
        }

        if ($request->hasFile('image')) {
            $optimizer = app(ImageOptimizationService::class);
            $path = $optimizer->optimize($request->file('image'), 'img/products');
            $data['image_url'] = 'storage/'.$path;
        }

        // Xử lý checkbox (Nếu không check thì request không gửi lên -> mặc định là 0)
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product = Product::create($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery')) {
            $optimizer = app(ImageOptimizationService::class);
            foreach ($request->file('gallery') as $file) {
                $path = $optimizer->optimize($file, 'img/products/gallery');

                $product->images()->create([
                    'image_path' => 'storage/'.$path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('pages.admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    // [FIX] Sử dụng UpdateProductRequest để tránh lỗi Unique
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Map 'quantity' from form to 'stock_quantity' in database
        if (isset($data['quantity'])) {
            $data['stock_quantity'] = $data['quantity'];
            unset($data['quantity']);
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_url && File::exists(public_path($product->image_url))) {
                File::delete(public_path($product->image_url));
            }

            $optimizer = app(ImageOptimizationService::class);
            $path = $optimizer->optimize($request->file('image'), 'img/products');
            $data['image_url'] = 'storage/'.$path;
        }

        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product->update($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery')) {
            $optimizer = app(ImageOptimizationService::class);
            foreach ($request->file('gallery') as $file) {
                $path = $optimizer->optimize($file, 'img/products/gallery');

                $product->images()->create([
                    'image_path' => 'storage/'.$path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_url && File::exists(public_path($product->image_url))) {
            File::delete(public_path($product->image_url));
        }

        // Soft delete - products with orders can now be deleted safely
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(ProductImage $image)
    {
        if (File::exists(public_path($image->image_path))) {
            File::delete(public_path($image->image_path));
        }

        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}

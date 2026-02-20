<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest; // [FIX] Dùng đúng tên file
use App\Http\Requests\UpdateProductRequest; // [FIX] Import file mới tạo
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

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

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $data['image_url'] = $path;
        }

        // Xử lý checkbox (Nếu không check thì request không gửi lên -> mặc định là 0)
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product = Product::create($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('products/gallery', $filename, 'public');

                $product->images()->create([
                    'image_path' => $path,
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

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            $oldImage = $product->getRawOriginal('image_url');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $data['image_url'] = $path;
        }

        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product->update($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('products/gallery', $filename, 'public');

                $product->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $oldImage = $product->getRawOriginal('image_url');
        if ($oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        // Soft delete - products with orders can now be deleted safely
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(ProductImage $image)
    {
        $rawPath = $image->getRawOriginal('image_path');
        if ($rawPath) {
            Storage::disk('public')->delete($rawPath);
        }

        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}

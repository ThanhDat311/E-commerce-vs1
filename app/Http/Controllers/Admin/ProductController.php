<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest; // [FIX] Dùng đúng tên file
use App\Http\Requests\UpdateProductRequest; // [FIX] Import file mới tạo
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('pages.admin.products.create', compact('categories'));
    }

    // [FIX] Sử dụng StoreProductRequest
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            if (! File::exists(public_path('img/products'))) {
                File::makeDirectory(public_path('img/products'), 0755, true);
            }

            $file->move(public_path('img/products'), $filename);
            $data['image_url'] = 'img/products/' . $filename;
        }

        // Xử lý checkbox (Nếu không check thì request không gửi lên -> mặc định là 0)
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('pages.admin.products.edit', compact('product', 'categories'));
    }

    // [FIX] Sử dụng UpdateProductRequest để tránh lỗi Unique
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image_url && File::exists(public_path($product->image_url))) {
                File::delete(public_path($product->image_url));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/products'), $filename);
            $data['image_url'] = 'img/products/' . $filename;
        }

        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product->update($data);

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
}

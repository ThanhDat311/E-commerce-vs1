<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    protected string $routePrefix = 'staff';

    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.staff.products.index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        return view('pages.staff.products.create', [
            'categories' => $categories,
        ]);
    }

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

        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product = Product::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/products/gallery'), $filename);

                $product->images()->create([
                    'image_path' => 'img/products/gallery/' . $filename,
                ]);
            }
        }

        return redirect()->route('staff.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('pages.staff.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
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

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                if (! File::exists(public_path('img/products/gallery'))) {
                    File::makeDirectory(public_path('img/products/gallery'), 0755, true);
                }

                $file->move(public_path('img/products/gallery'), $filename);

                $product->images()->create([
                    'image_path' => 'img/products/gallery/' . $filename,
                ]);
            }
        }

        return redirect()->route('staff.products.index')->with('success', 'Product updated successfully.');
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

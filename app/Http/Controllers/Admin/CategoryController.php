<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories with parent info and product count
        $categories = Category::with('parent')
            ->withCount('products')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        // Build tree structure
        $tree = $this->buildCategoryTree($categories);

        return view('admin.categories.index', compact('categories', 'tree'));
    }

    /**
     * Build hierarchical category tree
     */
    private function buildCategoryTree($categories)
    {
        $tree = [];

        foreach ($categories as $category) {
            if (is_null($category->parent_id)) {
                $tree[$category->id] = [
                    'category' => $category,
                    'children' => []
                ];
            }
        }

        foreach ($categories as $category) {
            if (!is_null($category->parent_id) && isset($tree[$category->parent_id])) {
                $tree[$category->parent_id]['children'][$category->id] = [
                    'category' => $category,
                    'children' => []
                ];
            }
        }

        return $tree;
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120'
        ]);

        // Auto-generate slug if not provided
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            if (!File::exists(public_path('img/categories'))) {
                File::makeDirectory(public_path('img/categories'), 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($data['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/categories'), $filename);
            $data['image_url'] = 'img/categories/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120'
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image_url && File::exists(public_path($category->image_url))) {
                File::delete(public_path($category->image_url));
            }

            if (!File::exists(public_path('img/categories'))) {
                File::makeDirectory(public_path('img/categories'), 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($data['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/categories'), $filename);
            $data['image_url'] = 'img/categories/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra nếu danh mục đang có sản phẩm thì không cho xóa
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category containing products.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}

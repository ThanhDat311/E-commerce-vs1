<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected string $routePrefix = 'staff';

    public function index()
    {
        $categories = Category::with('parent')
            ->withCount(['products', 'products as trashed_products_count' => function ($query) {
                $query->onlyTrashed();
            }])
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('pages.staff.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return view('pages.staff.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('image')) {
            if (! File::exists(public_path('img/categories'))) {
                File::makeDirectory(public_path('img/categories'), 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($data['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/categories'), $filename);
            $data['image_url'] = 'img/categories/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Category::create($data);

        return redirect()->route('staff.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('pages.staff.categories.edit', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('image')) {
            if ($category->image_url && File::exists(public_path($category->image_url))) {
                File::delete(public_path($category->image_url));
            }

            if (! File::exists(public_path('img/categories'))) {
                File::makeDirectory(public_path('img/categories'), 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($data['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/categories'), $filename);
            $data['image_url'] = 'img/categories/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $category->update($data);

        return redirect()->route('staff.categories.index')->with('success', 'Category updated successfully.');
    }
}

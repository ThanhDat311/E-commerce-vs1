<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::all();
    }

    public function getFeaturedCategories($limit = 6)
    {
        // Get categories ordered by the number of products they have
        return Category::withCount('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();
    }

    public function getTree()
    {
        // Assuming parent_id structure for multi-level
        // If simple structure, just return all or top level
        return Category::whereNull('parent_id')->with('children')->get();
    }
}

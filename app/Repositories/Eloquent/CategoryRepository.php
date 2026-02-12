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
        // Assuming 'is_featured' exists or just grab random/popular
        // If no 'is_featured' column, use latest or most products
        return Category::latest()->limit($limit)->get();
    }

    public function getTree()
    {
        // Assuming parent_id structure for multi-level
        // If simple structure, just return all or top level
        return Category::whereNull('parent_id')->with('children')->get();
    }
}

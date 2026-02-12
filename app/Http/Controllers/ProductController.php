<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->filterAndSort($request->all(), 12);

        // Fetch categories for sidebar filter (assuming we want all parent categories)
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('pages.store.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = $this->productRepository->findBySlug($slug);

        // Get related products
        $relatedProducts = $this->productRepository->getRelatedProducts($product);

        return view('pages.store.products.show', compact('product', 'relatedProducts'));
    }
}

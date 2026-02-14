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

        // Fetch categories for sidebar filter
        $categories = Category::whereNull('parent_id')->with('children')->withCount('products')->get();

        // Fetch brands (vendors) for sidebar
        $brands = $this->productRepository->getBrandsWithProductCount();

        // Fetch Min/Max price for slider
        $priceRange = $this->productRepository->getPriceRange();

        return view('pages.store.products.index', compact('products', 'categories', 'brands', 'priceRange'));
    }

    public function show($slug)
    {
        $product = $this->productRepository->findBySlug($slug);

        // Get related products
        $relatedProducts = $this->productRepository->getRelatedProducts($product);

        return view('pages.store.products.show', compact('product', 'relatedProducts'));
    }
}

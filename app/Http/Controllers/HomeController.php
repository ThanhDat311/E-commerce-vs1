<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ProductRepositoryInterface;

class HomeController extends Controller
{
    protected $productRepository;

    protected $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        \App\Repositories\Interfaces\CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        // Featured Categories
        $categories = $this->categoryRepository->getFeaturedCategories(6);

        // Products (Cached)
        $productsData = $this->productRepository->getHomePageProducts(8);
        $newProducts = $productsData['newProducts'];
        $arrivals = $productsData['arrivals'];

        // Flash Sales (Mocked for now or fetched if logic exists)
        // Ideally: Product::where('flash_sale', true)->get();
        // For now, let's use 'newProducts' as a placeholder or fetch 4 random
        $flashSales = \App\Models\Product::inRandomOrder()->take(4)->get();

        return view('pages.store.home', compact('categories', 'newProducts', 'arrivals', 'flashSales'));
    }
}

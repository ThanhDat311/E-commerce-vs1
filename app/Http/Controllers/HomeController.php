<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class HomeController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        // Get home page products from repository with Redis caching
        // Cache key: 'home_products' | TTL: 60 minutes (3600 seconds)
        // Cache is automatically invalidated when products are created, updated, or deleted
        $products = $this->productRepository->getHomePageProducts(8);

        return view('home', [
            'newProducts' => $products['newProducts'],
            'arrivals' => $products['arrivals']
        ]);
    }
}

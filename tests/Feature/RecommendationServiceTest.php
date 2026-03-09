<?php

use App\Models\Category;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(RecommendationService::class);
    Cache::flush(); // Ensure fresh cache for each test
});

it('returns empty purchased together when no purchase history', function () {
    $product = Product::factory()->create();
    $recommendations = $this->service->purchasedTogether($product);
    expect($recommendations)->toBeEmpty();
});

it('returns products in same category for category based strategy', function () {
    $category = Category::factory()->create();
    $product1 = Product::factory()->create(['category_id' => $category->id]);
    $product2 = Product::factory()->create(['category_id' => $category->id]);

    $recommendations = $this->service->categoryBased($product1);

    expect($recommendations)->toHaveCount(1);
    expect($recommendations->first()->id)->toBe($product2->id);
});

it('returns fallback trending products when no orders exist', function () {
    Product::factory()->count(5)->create();
    $trending = $this->service->trending();

    expect($trending)->toHaveCount(4); // Default limit is 4
});

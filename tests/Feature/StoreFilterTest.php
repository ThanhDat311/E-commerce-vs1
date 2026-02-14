<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_by_category()
    {
        $category = Category::factory()->create(['slug' => 'electronics']);
        $product1 = Product::factory()->create(['name' => 'Laptop', 'category_id' => $category->id]);
        $product2 = Product::factory()->create(['name' => 'T-Shirt']); // Different category

        $response = $this->get('/shop?category=electronics');

        $response->assertStatus(200);
        $response->assertSee('Laptop');
    }

    public function test_can_filter_by_price_range()
    {
        $product1 = Product::factory()->create(['name' => 'Cheap Item', 'price' => 50]);
        $product2 = Product::factory()->create(['name' => 'Expensive Item', 'price' => 500]);

        // Filter 10-100
        $response = $this->get('/shop?min_price=10&max_price=100');

        $response->assertStatus(200);
        $response->assertSee('Cheap Item');
        $response->assertDontSee('Expensive Item');
    }

    public function test_can_sort_by_price_asc()
    {
        $product1 = Product::factory()->create(['name' => 'Expensive', 'price' => 200]);
        $product2 = Product::factory()->create(['name' => 'Cheap', 'price' => 100]);

        $response = $this->get('/shop?sort=price_asc');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Cheap', 'Expensive']);
    }

    public function test_can_filter_by_brand()
    {
        $vendor1 = User::factory()->create(['name' => 'Vendor A']);
        $vendor2 = User::factory()->create(['name' => 'Vendor B']);

        $product1 = Product::factory()->create(['name' => 'Product A', 'vendor_id' => $vendor1->id]);
        $product2 = Product::factory()->create(['name' => 'Product B', 'vendor_id' => $vendor2->id]);

        $response = $this->get('/shop?brands[]=' . $vendor1->id);

        $response->assertStatus(200);
        $response->assertSee('Product A');
        $response->assertDontSee('Product B');
    }
}

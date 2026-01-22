<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_products()
    {
        $admin = User::factory()->create(['role_id' => 1]);
        $product = Product::factory()->create();

        $this->actingAs($admin);

        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
    }

    /** @test */
    public function vendor_can_only_view_own_products()
    {
        $vendor = User::factory()->create(['role_id' => 4]);
        $otherVendor = User::factory()->create(['role_id' => 4]);

        $ownProduct = Product::factory()->create(['vendor_id' => $vendor->id]);
        $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);

        $this->actingAs($vendor);

        $response = $this->getJson('/api/products');
        $response->assertStatus(200);

        // Should only see own product
        $data = $response->json();
        $this->assertCount(1, $data['data']);
        $this->assertEquals($ownProduct->id, $data['data'][0]['id']);
    }

    /** @test */
    public function vendor_can_create_products()
    {
        $vendor = User::factory()->create(['role_id' => 4]);
        $category = \App\Models\Category::factory()->create();

        $this->actingAs($vendor);

        $productData = [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'price' => 100,
            'stock_quantity' => 10,
        ];

        $response = $this->postJson('/api/products', $productData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'vendor_id' => $vendor->id,
        ]);
    }

    /** @test */
    public function customer_cannot_create_products()
    {
        $customer = User::factory()->create(['role_id' => 3]);

        $this->actingAs($customer);

        $response = $this->postJson('/api/products', []);
        $response->assertStatus(403);
    }

    /** @test */
    public function vendor_can_view_orders_containing_their_products()
    {
        $vendor = User::factory()->create(['role_id' => 4]);
        $customer = User::factory()->create(['role_id' => 3]);

        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        $order = Order::factory()->create(['user_id' => $customer->id]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($vendor);

        $response = $this->getJson('/api/orders');
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertCount(1, $data['data']);
    }
}
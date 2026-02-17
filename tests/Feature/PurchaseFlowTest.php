<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseFlowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the complete purchase flow:
     * Select Product -> Add to Cart -> Checkout -> Create Order -> Save DB -> Update Inventory
     */
    public function test_complete_purchase_flow_updates_inventory()
    {
        // 1. ARRANGE
        // Create user
        $user = User::factory()->create();

        // Create category
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);

        // Create product with Initial Stock = 10
        $initialStock = 10;
        $product = Product::create([
            'name' => 'iPhone 15',
            'slug' => 'iphone-15',
            'sku' => 'IPHONE-15-128GB',
            'description' => 'Latest iPhone',
            'price' => 1000,
            'sale_price' => 900,
            'stock_quantity' => $initialStock,
            'category_id' => $category->id,
            'image_url' => 'products/iphone.jpg',
            'is_active' => true,
        ]);

        // 2. ACT
        // Login
        $this->actingAs($user);

        // A. Add to Cart (GET request based on routes/web.php)
        $response = $this->get(route('cart.add', ['id' => $product->id, 'quantity' => 1]));
        $response->assertSessionHas('success'); // Ensure added successfully

        // Verify Cart Session
        $cart = session('cart');
        $this->assertIsArray($cart);
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(1, $cart[$product->id]['quantity']);

        // B. Visit Checkout Page (Ensure Blade renders correctly)
        $response = $this->get(route('checkout.index'));
        $response->assertStatus(200);

        // C. Checkout (Place Order)
        $orderData = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $user->email,
            'phone' => '1234567890',
            'address' => '123 Test St, Test City',
            'payment_method' => 'cod', // Cash on Delivery
            'note' => 'Test Order',
        ];

        $response = $this->post(route('cart.placeOrder'), $orderData);

        // 3. ASSERT
        // Check Redirect to Success Page
        // Check Redirect to Success Page
        $response->assertRedirect(route('checkout.success'));
        $response->assertSessionHas('order_id'); // Controller flashes order_id, not success message

        // Check Database for Order
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'email' => $user->email,
            'total' => 1003, // 1000 (Regular Price) + 3 (Shipping). Sale price requires Flash Sale.
            'payment_method' => 'cod',
            'order_status' => 'pending',
        ]);

        // Check Database for Order Items
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 1000,
        ]);

        // Check Inventory Update (Stock should be 9)
        $this->assertEquals($initialStock - 1, $product->fresh()->stock_quantity, 'Inventory was not updated correctly.');
    }
}

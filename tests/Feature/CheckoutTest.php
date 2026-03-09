<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    // Sử dụng để fake dữ liệu (tên, email, sđt)
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test case: User cannot access checkout page if cart is empty.
     * Logic: Should redirect to Shop page.
     */
    public function test_guest_cannot_access_checkout_with_empty_cart()
    {
        // 1. Arrange: Ensure session is empty
        session(['cart' => []]);

        // 2. Act: Try to access checkout route
        $response = $this->get(route('checkout.index'));

        // 3. Assert: Check redirection to 'shop' route
        $response->assertStatus(302);
        $response->assertRedirect(route('shop.index'));
    }

    /**
     * Test case: User can access checkout page with items in cart.
     * Logic: Should return status 200 and display correct calculations.
     */
    public function test_guest_can_access_checkout_with_items()
    {
        // 1. Arrange: Create data in DB
        $category = Category::factory()->create(['id' => 1]);
        $product = Product::factory()->create([
            'id' => 1,
            'category_id' => $category->id,
            'price' => 100,
            'stock_quantity' => 10,
        ]);

        // Mock cart data in session
        $cartData = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 2, // Subtotal = 200
                'image' => 'img/test.png',
            ],
        ];
        session(['cart' => $cartData]);

        // 2. Act
        $response = $this->get(route('checkout.index'));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertViewIs('checkout');

        // Verify view data calculation: Subtotal (200) + Shipping (3) = 203
        $response->assertViewHas('subTotal', 200);
        $response->assertViewHas('total', 203);
    }

    /**
     * Test case: Validation fails when required fields are missing.
     * Logic: Should redirect back with errors.
     */
    public function test_place_order_requires_validation()
    {
        // 1. Arrange: Cart has items (auth required now)
        $user = User::factory()->create();
        $category = Category::factory()->create(['id' => 1]);
        $product = Product::factory()->create(['id' => 1, 'category_id' => 1, 'stock_quantity' => 10]);
        session(['cart' => [$product->id => ['id' => $product->id, 'price' => 10, 'quantity' => 1]]]);

        // 2. Act: Submit empty data as authenticated user
        $response = $this->actingAs($user)->post(route('cart.placeOrder'), []);

        // 3. Assert: Check validation errors for required fields
        $response->assertSessionHasErrors(['first_name', 'phone', 'email', 'address']);
    }

    /**
     * Test case: Order is placed successfully with valid data.
     * Logic: Cart should be cleared, and user redirected to success page.
     */
    public function test_place_order_successfully()
    {
        // 1. Arrange: Create data in DB + auth user
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'name' => 'Real Product',
            'sku' => 'TEST-SKU-001',
            'price' => 100,
            'stock_quantity' => 50,
            'category_id' => $category->id,
            'description' => 'Test desc',
            'image_url' => 'img/test.png',
        ]);

        // 2. Mock Session Giỏ hàng
        session(['cart' => [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => 'img/test.png',
            ],
        ]]);

        // Fake form data
        $formData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => '0901234567',
            'address' => $this->faker->address,
            'payment_method' => 'cod',
        ];

        // 3. Act as authenticated user
        $response = $this->actingAs($user)->post(route('cart.placeOrder'), $formData);

        // 4. Assert
        $response->assertRedirect(route('checkout.success'));
        $response->assertSessionHas('success', 'Đơn hàng đã được đặt thành công!');
        $this->assertEmpty(session('cart'));
    }
}

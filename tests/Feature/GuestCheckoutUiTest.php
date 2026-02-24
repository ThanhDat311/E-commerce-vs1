<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\RiskManagementService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can access checkout page with items in cart', function () {
    // Arrange: create a product and add to cart session
    $category = Category::factory()->create();
    $product = Product::factory()->for($category)->create([
        'price' => 50.00,
        'stock_quantity' => 10,
    ]);

    session(['cart' => [
        $product->id => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => 'img/test.png',
        ],
    ]]);

    // Act
    $response = $this->get(route('checkout.index'));

    // Assert: guest should be able to see checkout page
    $response->assertStatus(200);
    $response->assertViewIs('checkout');
    $response->assertSee('Contact Information');
    $response->assertSee('Already have an account?');
});

test('guest is redirected when checkout cart is empty', function () {
    session(['cart' => []]);

    $response = $this->get(route('checkout.index'));

    $response->assertRedirect(route('shop.index'));
});

test('authenticated user sees saved addresses on checkout', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();
    $product = Product::factory()->for($category)->create([
        'price' => 50.00,
        'stock_quantity' => 10,
    ]);

    session(['cart' => [
        $product->id => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => 'img/test.png',
        ],
    ]]);

    $response = $this->actingAs($user)->get(route('checkout.index'));

    $response->assertStatus(200);
    $response->assertViewIs('checkout');
    $response->assertSee('Shipping Address');
});

test('guest user can process checkout successfully', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->for($category)->create([
        'price' => 100.00,
        'stock_quantity' => 10,
    ]);

    $mockData = [
        'cartItems' => [
            [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => 100,
                'total' => 100,
            ],
        ],
        'total' => 100,
    ];

    $this->mock(CartService::class, function ($mock) use ($mockData) {
        $mock->shouldReceive('getCartDetails')->andReturn($mockData);
        $mock->shouldReceive('clearCart')->once();
    });

    $this->mock(RiskManagementService::class, function ($mock) {
        $mock->shouldReceive('assessOrderRisk')->andReturn([
            'allowed' => true,
            'score' => 0.2,
            'reason' => 'Guest',
            'log_id' => null,
        ]);
    });

    $response = $this->post(route('checkout.process'), [
        'first_name' => 'Guest',
        'last_name' => 'User',
        'email' => 'guest@example.com',
        'phone' => '0987654321',
        'address' => 'Guest House, City, Country',
        'payment_method' => 'cod',
    ]);

    $response->assertRedirect(route('checkout.success'));
    $this->assertDatabaseHas('orders', [
        'user_id' => null,
        'email' => 'guest@example.com',
    ]);
});

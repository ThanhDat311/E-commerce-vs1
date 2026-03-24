<?php

/** @var \Tests\TestCase $this */

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->vendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);
    $this->otherVendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);
    $this->customer = User::factory()->create(['role_id' => 3]);

    $this->category = Category::factory()->create();

    // Product belonging to $this->vendor
    $this->product = Product::factory()->create([
        'vendor_id' => $this->vendor->id,
        'category_id' => $this->category->id,
    ]);

    // Order containing vendor's product
    $this->order = Order::factory()->create([
        'user_id' => $this->customer->id,
        'order_status' => 'pending',
    ]);

    OrderItem::factory()->create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
    ]);

    // Order with another vendor's product (vendor should NOT see this)
    $this->otherProduct = Product::factory()->create([
        'vendor_id' => $this->otherVendor->id,
        'category_id' => $this->category->id,
    ]);

    $this->otherOrder = Order::factory()->create([
        'user_id' => $this->customer->id,
    ]);

    OrderItem::factory()->create([
        'order_id' => $this->otherOrder->id,
        'product_id' => $this->otherProduct->id,
    ]);
});

it('vendor can list orders containing their products', function () {
    $this->actingAs($this->vendor);

    $response = $this->getJson('/api/v1/vendor/orders');

    $response->assertOk()
        ->assertJsonFragment(['id' => $this->order->id]);
});

it('vendor cannot see orders with no vendor products', function () {
    $this->actingAs($this->vendor);

    $response = $this->getJson('/api/v1/vendor/orders');

    $response->assertOk()
        ->assertJsonMissing(['id' => $this->otherOrder->id]);
});

it('vendor can view an order containing their product', function () {
    $this->actingAs($this->vendor);

    $this->getJson("/api/v1/vendor/orders/{$this->order->id}")
        ->assertOk()
        ->assertJsonFragment(['id' => $this->order->id]);
});

it('vendor cannot view an order with no vendor products', function () {
    $this->actingAs($this->vendor);

    $this->getJson("/api/v1/vendor/orders/{$this->otherOrder->id}")
        ->assertForbidden();
});

it('vendor can update order status to shipped', function () {
    $this->actingAs($this->vendor);

    $this->patchJson("/api/v1/vendor/orders/{$this->order->id}/status", [
        'order_status' => 'shipped',
    ])->assertOk()
        ->assertJsonFragment(['order_status' => 'shipped']);

    $this->assertDatabaseHas('orders', [
        'id' => $this->order->id,
        'order_status' => 'shipped',
    ]);
});

it('vendor cannot set an invalid order status', function () {
    $this->actingAs($this->vendor);

    $this->patchJson("/api/v1/vendor/orders/{$this->order->id}/status", [
        'order_status' => 'cancelled',
    ])->assertUnprocessable();
});

it('vendor cannot update order status of another vendor', function () {
    $this->actingAs($this->vendor);

    $this->patchJson("/api/v1/vendor/orders/{$this->otherOrder->id}/status", [
        'order_status' => 'shipped',
    ])->assertForbidden();
});

it('unauthenticated user cannot access vendor orders API', function () {
    $this->getJson('/api/v1/vendor/orders')->assertUnauthorized();
});

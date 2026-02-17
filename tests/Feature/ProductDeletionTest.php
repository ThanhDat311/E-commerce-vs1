<?php

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Scopes\VendorScope;

test('vendor can soft delete product with order items', function () {
    $vendor = User::factory()->create([
        'role_id' => 4,
        'is_active' => 1,
        'status' => 'active',
    ]);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    // Create an order with this product
    $order = Order::factory()->create();
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($vendor)
        ->delete(route('vendor.products.destroy', $product))
        ->assertRedirect(route('vendor.products.index'))
        ->assertSessionHas('success');

    // Product should be soft deleted (deleted_at is set)
    $this->assertSoftDeleted('products', ['id' => $product->id]);

    // Product should still exist in database with deleted_at timestamp
    $this->assertDatabaseHas('products', ['id' => $product->id]);
});

test('vendor can soft delete product without order items', function () {
    $vendor = User::factory()->create([
        'role_id' => 4,
        'is_active' => 1,
        'status' => 'active',
    ]);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $this->actingAs($vendor)
        ->delete(route('vendor.products.destroy', $product))
        ->assertRedirect(route('vendor.products.index'))
        ->assertSessionHas('success');

    // Product should be soft deleted
    $this->assertSoftDeleted('products', ['id' => $product->id]);
});

test('admin can soft delete product with order items', function () {
    $admin = User::factory()->create([
        'role_id' => 1,
        'is_active' => 1,
        'status' => 'active',
    ]);

    $product = Product::factory()->create();

    // Create an order with this product
    $order = Order::factory()->create();
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('success');

    // Product should be soft deleted
    $this->assertSoftDeleted('products', ['id' => $product->id]);
});

test('admin can soft delete product without order items', function () {
    $admin = User::factory()->create([
        'role_id' => 1,
        'is_active' => 1,
        'status' => 'active',
    ]);

    $product = Product::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('success');

    // Product should be soft deleted
    $this->assertSoftDeleted('products', ['id' => $product->id]);
});

test('soft deleted products do not appear in queries', function () {
    $vendor = User::factory()->create([
        'role_id' => 4,
        'is_active' => 1,
        'status' => 'active',
    ]);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $productId = $product->id;

    // Delete the product
    $product->delete();

    // Product should not appear in normal queries
    $this->actingAs($vendor);
    $this->assertNull(Product::find($productId));

    // But should appear when including trashed
    $this->assertNotNull(Product::withTrashed()->find($productId));
});

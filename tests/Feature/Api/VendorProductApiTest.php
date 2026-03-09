<?php

/** @var \Tests\TestCase $this */

use App\Models\Category;
use App\Models\Product;
use App\Models\User;


beforeEach(function () {
    $this->vendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);
    $this->otherVendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);
    $this->category = Category::factory()->create();

    $this->product = Product::factory()->create([
        'vendor_id' => $this->vendor->id,
        'category_id' => $this->category->id,
    ]);
    $this->otherProduct = Product::factory()->create([
        'vendor_id' => $this->otherVendor->id,
        'category_id' => $this->category->id,
    ]);
});

it('vendor can list their own products via the API', function () {
    $this->actingAs($this->vendor);

    $response = $this->getJson('/api/v1/vendor/products');

    $response->assertOk()
        ->assertJsonFragment(['id' => $this->product->id])
        ->assertJsonMissing(['id' => $this->otherProduct->id]);
});

it('unauthenticated user cannot access vendor products API', function () {
    $this->getJson('/api/v1/vendor/products')->assertUnauthorized();
});

it('customer cannot access the vendor product API', function () {
    $customer = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $this->actingAs($customer);

    $this->getJson('/api/v1/vendor/products')->assertForbidden();
});

it('vendor can view their own product detail', function () {
    $this->actingAs($this->vendor);

    $this->getJson("/api/v1/vendor/products/{$this->product->id}")
        ->assertOk()
        ->assertJsonFragment(['id' => $this->product->id]);
});

it('vendor cannot view another vendor product detail', function () {
    $this->actingAs($this->vendor);

    $this->getJson("/api/v1/vendor/products/{$this->otherProduct->id}")
        ->assertNotFound();
});

it('vendor can create a product via the API', function () {
    $this->actingAs($this->vendor);

    $payload = [
        'name' => 'Test API Product',
        'sku' => 'TEST-SKU-123',
        'price' => 99.99,
        'quantity' => 10,
        'category_id' => $this->category->id,
        'description' => 'Test description for product.',
    ];

    $this->postJson('/api/v1/vendor/products', $payload)
        ->assertCreated()
        ->assertJsonFragment(['name' => 'Test API Product']);

    $this->assertDatabaseHas('products', [
        'name' => 'Test API Product',
        'vendor_id' => $this->vendor->id,
    ]);
});

it('vendor can update their own product', function () {
    $this->actingAs($this->vendor);

    $this->putJson("/api/v1/vendor/products/{$this->product->id}", [
        'name' => 'Updated Product Name',
        'price' => 149.99,
        'quantity' => 20,
        'category_id' => $this->category->id,
        'description' => 'Updated description.',
    ])->assertOk()->assertJsonFragment(['name' => 'Updated Product Name']);
});

it('vendor cannot update another vendor product', function () {
    $this->actingAs($this->vendor);

    $this->putJson("/api/v1/vendor/products/{$this->otherProduct->id}", [
        'name' => 'Hijacked Name',
        'price' => 1.00,
        'quantity' => 1,
        'category_id' => $this->category->id,
        'description' => 'Hijacked.',
    ])->assertNotFound();
});

it('vendor can soft-delete their own product', function () {
    $this->actingAs($this->vendor);

    $this->deleteJson("/api/v1/vendor/products/{$this->product->id}")
        ->assertOk();

    $this->assertSoftDeleted('products', ['id' => $this->product->id]);
});

it('vendor cannot delete another vendor product', function () {
    $this->actingAs($this->vendor);

    $this->deleteJson("/api/v1/vendor/products/{$this->otherProduct->id}")
        ->assertNotFound();
});

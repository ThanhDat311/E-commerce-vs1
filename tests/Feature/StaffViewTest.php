<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('staff can view products index page', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);
    Product::factory()->count(3)->create();

    $response = $this->actingAs($staff)->get(route('staff.products.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.staff.products.index');
    $response->assertViewHas('products');
});

test('staff can view products create page', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);

    $response = $this->actingAs($staff)->get(route('staff.products.create'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.staff.products.create');
});

test('staff can view categories index page', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);
    Category::factory()->count(3)->create();

    $response = $this->actingAs($staff)->get(route('staff.categories.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.staff.categories.index');
    $response->assertViewHas('categories');
});

test('staff can view orders index page', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);

    $response = $this->actingAs($staff)->get(route('staff.orders.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.staff.orders.index');
    $response->assertViewHas('orders');
});

test('staff cannot access admin products page', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);

    $response = $this->actingAs($staff)->get(route('admin.products.index'));

    $response->assertStatus(403);
});

test('customer cannot access staff products page', function () {
    $customer = User::factory()->create(['role_id' => 3, 'is_active' => true]);

    $response = $this->actingAs($customer)->get(route('staff.products.index'));

    $response->assertStatus(403);
});

test('staff cannot delete products', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);
    $product = Product::factory()->create();

    $response = $this->actingAs($staff)->delete(route('staff.products.index') . '/' . $product->id);

    $response->assertStatus(405);
});

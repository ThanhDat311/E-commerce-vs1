<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view products index page', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    Product::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.products.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.products.index');
    $response->assertViewHas('products');
});

test('admin can view categories index page', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    Category::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.categories.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.categories.index');
    $response->assertViewHas('categories');
});

test('admin can view users index page', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    User::factory()->count(5)->create();

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.users.index');
    $response->assertViewHas('users');
});

test('non-admin cannot access admin products page', function () {
    $customer = User::factory()->create(['role_id' => 3]);

    $response = $this->actingAs($customer)->get(route('admin.products.index'));

    $response->assertStatus(403);
});

<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use App\Models\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('homepage loads successfully', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('product view loads successfully', function () {
    // Create a product to view
    // Assuming factories are set up. If not, we create manually.
    // Creating dependencies first if needed
    if (Role::count() == 0) {
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    // Ensure we have a vendor to assign the product to
    $vendor = User::factory()->create(['role_id' => 4]); // 4 is vendor

    // Create category if needed
    $category = Category::first();
    if (!$category) {
        $category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
    }

    $product = Product::create([
        'name' => 'Test Product',
        'sku' => 'TEST-SKU-' . rand(1000, 9999),
        'price' => 100,
        'stock_quantity' => 10,
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
        'description' => 'Test Description'
    ]);

    $response = $this->get('/product/' . $product->id);

    $response->assertStatus(200);
    $response->assertSee($product->name);
});

test('guest cannot access admin or vendor portals', function () {
    $response = $this->get('/admin');
    $response->assertStatus(302); // Redirect to login
    $response->assertRedirect('/login');

    $response = $this->get('/vendor');
    $response->assertStatus(302); // Redirect to login
    $response->assertRedirect('/login');
});

test('dropdown menu shows profile link when logged in', function () {
    // Create a regular user
    $user = User::factory()->create(['role_id' => 3]); // 3 is customer

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);

    // Check for "Profile" or "My Profile" in the response
    // The visual guide mentions "Profile" in dropdown
    $response->assertSee('Profile');
    $response->assertSee('Logout');
});

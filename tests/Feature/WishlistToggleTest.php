<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;

test('authenticated user can add product to wishlist', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'inWishlist' => true,
            'message' => 'Product added to wishlist',
        ]);

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('authenticated user can remove product from wishlist', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    // Add to wishlist first
    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'inWishlist' => false,
            'message' => 'Product removed from wishlist',
        ]);

    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('unauthenticated user cannot toggle wishlist', function () {
    $product = Product::factory()->create();

    $response = $this->postJson(route('wishlist.toggle'), [
        'product_id' => $product->id,
    ]);

    $response->assertStatus(401);
});

test('product detail page shows correct wishlist state for authenticated user', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    // Add product to wishlist
    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('shop.show', $product->slug));

    $response->assertStatus(200)
        ->assertSee('data-in-wishlist="true"', false);
});

test('product detail page shows add to wishlist for non-wishlisted product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('shop.show', $product->slug));

    $response->assertStatus(200)
        ->assertSee('data-in-wishlist="false"', false);
});

test('product detail page shows login link for guest users', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('shop.show', $product->slug));

    $response->assertStatus(200)
        ->assertSee(route('login'), false)
        ->assertSee('Login to add to wishlist', false);
});

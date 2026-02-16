<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;

test('wishlist page is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/wishlist');

    $response->assertOk();
    $response->assertViewIs('profile.wishlist.index');
});

test('guest cannot access wishlist page', function () {
    $response = $this->get('/wishlist');

    $response->assertRedirect('/login');
});

test('user can add a product to wishlist', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->postJson('/wishlist/add', [
        'product_id' => $product->id,
    ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('user can remove a product from wishlist', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($user)->delete("/wishlist/remove/{$product->id}");

    $response->assertRedirect(route('wishlist.index'));

    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('user can toggle a product in wishlist', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    // Toggle on
    $this->actingAs($user)->post('/wishlist/toggle', [
        'product_id' => $product->id,
    ]);

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    // Toggle off
    $this->actingAs($user)->post('/wishlist/toggle', [
        'product_id' => $product->id,
    ]);

    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('adding duplicate product to wishlist does not create duplicate', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->postJson('/wishlist/add', ['product_id' => $product->id]);
    $this->actingAs($user)->postJson('/wishlist/add', ['product_id' => $product->id]);

    expect(Wishlist::where('user_id', $user->id)->where('product_id', $product->id)->count())->toBe(1);
});

<?php

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot post a review', function () {
    $product = Product::factory()->create();

    $response = $this->post(route('reviews.store', $product), [
        'rating' => 5,
        'comment' => 'Great product!',
    ]);

    $response->assertRedirect(route('login'));
    $this->assertDatabaseCount('reviews', 0);
});

test('authenticated user can submit a review', function () {
    $user = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->post(route('reviews.store', $product), [
        'rating' => 4,
        'comment' => 'Really good quality!',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('reviews', [
        'product_id' => $product->id,
        'user_id' => $user->id,
        'rating' => 4,
    ]);
});

test('user cannot submit a review without rating', function () {
    $user = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->post(route('reviews.store', $product), [
        'comment' => 'Good product',
    ]);

    $response->assertSessionHasErrors('rating');
    $this->assertDatabaseCount('reviews', 0);
});

test('user cannot submit rating outside 1-5', function () {
    $user = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->post(route('reviews.store', $product), [
        'rating' => 6,
    ]);

    $response->assertSessionHasErrors('rating');
});

test('user cannot review the same product twice', function () {
    $user = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $product = Product::factory()->create();

    Review::create([
        'product_id' => $product->id,
        'user_id' => $user->id,
        'rating' => 4,
        'comment' => 'First review',
    ]);

    $response = $this->actingAs($user)->post(route('reviews.store', $product), [
        'rating' => 5,
        'comment' => 'Trying again',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('review_error');
    $this->assertDatabaseCount('reviews', 1);
});

<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Session;

test('cart page shows real related products not placeholders', function () {
    $user = User::factory()->create();
    Product::factory()->count(5)->create();

    $response = $this->actingAs($user)->get(route('cart.index'));

    $response->assertStatus(200);
    $response->assertViewHas('relatedProducts');

    $relatedProducts = $response->viewData('relatedProducts');
    expect($relatedProducts)->toHaveCount(4);
    expect($relatedProducts->first()->name)->not->toBe('Product Name 1');
});

test('related products do not include items already in cart', function () {
    $user = User::factory()->create();
    $inCartProduct = Product::factory()->create();
    Product::factory()->count(5)->create();

    Session::put('cart', [
        $inCartProduct->id => ['id' => $inCartProduct->id, 'quantity' => 1],
    ]);

    $response = $this->actingAs($user)->get(route('cart.index'));

    $response->assertStatus(200);

    $relatedIds = $response->viewData('relatedProducts')->pluck('id')->toArray();

    expect($relatedIds)->not->toContain($inCartProduct->id);
});

test('cart page renders correctly when cart is empty', function () {
    $user = User::factory()->create();
    Session::forget('cart');

    $response = $this->actingAs($user)->get(route('cart.index'));

    $response->assertStatus(200);
    $response->assertSee('Your cart is empty');
});

test('unauthenticated user is redirected away from cart', function () {
    $response = $this->get(route('cart.index'));

    $response->assertRedirect(route('login'));
});

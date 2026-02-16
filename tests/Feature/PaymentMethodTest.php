<?php

use App\Models\PaymentMethod;
use App\Models\User;

test('payment methods page is displayed for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/my-payment-methods');

    $response->assertOk();
    $response->assertViewIs('profile.payment-methods.index');
});

test('guest cannot access payment methods page', function () {
    $response = $this->get('/my-payment-methods');

    $response->assertRedirect('/login');
});

test('user can store a new payment method with visa card', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/my-payment-methods', [
        'cardholder_name' => 'John Doe',
        'card_number' => '4111111111111111',
        'expiry_month' => 12,
        'expiry_year' => (int) date('Y') + 2,
        'is_default' => '0',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('payment_methods', [
        'user_id' => $user->id,
        'cardholder_name' => 'John Doe',
        'card_brand' => 'visa',
        'last_four' => '1111',
        'is_default' => true, // first card is always default
    ]);
});

test('card brand is detected correctly for mastercard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/my-payment-methods', [
        'cardholder_name' => 'Jane Doe',
        'card_number' => '5500000000000004',
        'expiry_month' => 6,
        'expiry_year' => (int) date('Y') + 1,
    ]);

    $card = PaymentMethod::where('user_id', $user->id)->first();
    expect($card->card_brand)->toBe('mastercard');
    expect($card->last_four)->toBe('0004');
});

test('user can delete a non-default payment method', function () {
    $user = User::factory()->create();

    $default = PaymentMethod::create([
        'user_id' => $user->id,
        'cardholder_name' => 'Default Card',
        'card_brand' => 'visa',
        'last_four' => '1234',
        'expiry_month' => 12,
        'expiry_year' => (int) date('Y') + 1,
        'is_default' => true,
    ]);

    $other = PaymentMethod::create([
        'user_id' => $user->id,
        'cardholder_name' => 'Other Card',
        'card_brand' => 'mastercard',
        'last_four' => '5678',
        'expiry_month' => 6,
        'expiry_year' => (int) date('Y') + 1,
        'is_default' => false,
    ]);

    $response = $this->actingAs($user)->delete("/my-payment-methods/{$other->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('payment_methods', ['id' => $other->id]);
});

test('user cannot delete default card when other cards exist', function () {
    $user = User::factory()->create();

    $default = PaymentMethod::create([
        'user_id' => $user->id,
        'cardholder_name' => 'Default Card',
        'card_brand' => 'visa',
        'last_four' => '1234',
        'expiry_month' => 12,
        'expiry_year' => (int) date('Y') + 1,
        'is_default' => true,
    ]);

    PaymentMethod::create([
        'user_id' => $user->id,
        'cardholder_name' => 'Other Card',
        'card_brand' => 'mastercard',
        'last_four' => '5678',
        'expiry_month' => 6,
        'expiry_year' => (int) date('Y') + 1,
        'is_default' => false,
    ]);

    $response = $this->actingAs($user)->delete("/my-payment-methods/{$default->id}");

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('payment_methods', ['id' => $default->id]);
});

test('user can set a card as default', function () {
    $user = User::factory()->create();

    $card1 = PaymentMethod::create([
        'user_id' => $user->id,
        'cardholder_name' => 'Card 1',
        'card_brand' => 'visa',
        'last_four' => '1111',
        'expiry_month' => 12,
        'expiry_year' => (int) date('Y') + 1,
        'is_default' => true,
    ]);

    $card2 = PaymentMethod::create([
        'user_id' => $user->id,
        'cardholder_name' => 'Card 2',
        'card_brand' => 'mastercard',
        'last_four' => '2222',
        'expiry_month' => 6,
        'expiry_year' => (int) date('Y') + 1,
        'is_default' => false,
    ]);

    $response = $this->actingAs($user)->post("/my-payment-methods/{$card2->id}/default");

    $response->assertRedirect();

    $card1->refresh();
    $card2->refresh();

    expect($card1->is_default)->toBeFalsy();
    expect($card2->is_default)->toBeTrue();
});

test('user cannot access another users payment method', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $card = PaymentMethod::create([
        'user_id' => $user2->id,
        'cardholder_name' => 'User2 Card',
        'card_brand' => 'visa',
        'last_four' => '9999',
        'expiry_month' => 12,
        'expiry_year' => (int) date('Y') + 1,
        'is_default' => true,
    ]);

    $response = $this->actingAs($user1)->delete("/my-payment-methods/{$card->id}");

    $response->assertForbidden();
});

test('store payment method validates required fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/my-payment-methods', []);

    $response->assertSessionHasErrors(['cardholder_name', 'card_number', 'expiry_month', 'expiry_year']);
});

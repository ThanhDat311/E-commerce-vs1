<?php

use App\Models\Address;
use App\Models\User;

test('addresses page is displayed for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/my-addresses');

    $response->assertOk();
    $response->assertViewIs('profile.addresses.index');
});

test('guest cannot access addresses page', function () {
    $response = $this->get('/my-addresses');

    $response->assertRedirect('/login');
});

test('user can store a new address', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/my-addresses', [
        'recipient_name' => 'John Doe',
        'phone_contact' => '+1 555 000 0000',
        'address_line1' => '123 Main St',
        'city' => 'New York',
        'state' => 'NY',
        'zip_code' => '10001',
        'country' => 'United States',
        'address_label' => 'Home',
        'is_default' => '1',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('addresses', [
        'user_id' => $user->id,
        'recipient_name' => 'John Doe',
        'city' => 'New York',
        'address_label' => 'Home',
    ]);
});

test('first address is automatically set as default', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/my-addresses', [
        'recipient_name' => 'Jane Doe',
        'phone_contact' => '+1 555 111 1111',
        'address_line1' => '456 Oak Ave',
        'city' => 'Los Angeles',
        'country' => 'United States',
    ]);

    $address = Address::where('user_id', $user->id)->first();
    expect($address->is_default)->toBeTrue();
});

test('user can update their address', function () {
    $user = User::factory()->create();
    $address = Address::create([
        'user_id' => $user->id,
        'recipient_name' => 'Old Name',
        'phone_contact' => '000',
        'address_line1' => 'Old St',
        'city' => 'Old City',
        'country' => 'US',
        'is_default' => true,
    ]);

    $response = $this->actingAs($user)->put("/my-addresses/{$address->id}", [
        'recipient_name' => 'New Name',
        'phone_contact' => '111',
        'address_line1' => 'New St',
        'city' => 'New City',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $address->refresh();
    expect($address->recipient_name)->toBe('New Name');
    expect($address->city)->toBe('New City');
});

test('user can delete their address', function () {
    $user = User::factory()->create();
    $address = Address::create([
        'user_id' => $user->id,
        'recipient_name' => 'To Delete',
        'phone_contact' => '000',
        'address_line1' => 'Some St',
        'city' => 'Some City',
        'country' => 'US',
        'is_default' => false,
    ]);

    $response = $this->actingAs($user)->delete("/my-addresses/{$address->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
});

test('user can set an address as default', function () {
    $user = User::factory()->create();

    $addr1 = Address::create([
        'user_id' => $user->id,
        'recipient_name' => 'Addr 1',
        'phone_contact' => '111',
        'address_line1' => 'St 1',
        'city' => 'City 1',
        'country' => 'US',
        'is_default' => true,
    ]);

    $addr2 = Address::create([
        'user_id' => $user->id,
        'recipient_name' => 'Addr 2',
        'phone_contact' => '222',
        'address_line1' => 'St 2',
        'city' => 'City 2',
        'country' => 'US',
        'is_default' => false,
    ]);

    $response = $this->actingAs($user)->post("/my-addresses/{$addr2->id}/default");

    $response->assertRedirect();

    $addr1->refresh();
    $addr2->refresh();

    expect($addr1->is_default)->toBeFalsy();
    expect($addr2->is_default)->toBeTrue();
});

test('store address validates required fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/my-addresses', []);

    $response->assertSessionHasErrors(['recipient_name', 'phone_contact', 'address_line1', 'city']);
});

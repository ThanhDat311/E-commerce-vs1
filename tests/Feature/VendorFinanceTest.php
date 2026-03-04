<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('vendor can access finance page', function () {
    $vendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);

    $response = $this->actingAs($vendor)->get(route('vendor.finance.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.vendor.finance.index');
});

test('customer cannot access vendor finance page', function () {
    $customer = User::factory()->create(['role_id' => 3, 'is_active' => true]);

    $response = $this->actingAs($customer)->get(route('vendor.finance.index'));

    $response->assertStatus(403);
});

test('staff cannot access vendor finance page', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);

    $response = $this->actingAs($staff)->get(route('vendor.finance.index'));

    $response->assertStatus(403);
});

test('guest is redirected from vendor finance page', function () {
    $response = $this->get(route('vendor.finance.index'));

    $response->assertRedirect(route('login'));
});

<?php

/** @var \Tests\TestCase $this */

use App\Models\CommissionSetting;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role_id' => 1, 'is_active' => true]);
    $this->vendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);
});

it('shows vendor listing for admin', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.vendors.index'))
        ->assertStatus(200)
        ->assertViewIs('pages.admin.vendors.index')
        ->assertSee($this->vendor->name);
});

it('can search vendors by name', function () {
    $other = User::factory()->create(['role_id' => 4, 'name' => 'UniqueVendorName']);

    $this->actingAs($this->admin)
        ->get(route('admin.vendors.index', ['search' => 'UniqueVendorName']))
        ->assertStatus(200)
        ->assertSee('UniqueVendorName')
        ->assertDontSee($this->vendor->name);
});

it('can filter vendors by status', function () {
    $suspended = User::factory()->create(['role_id' => 4, 'is_active' => false]);

    $this->actingAs($this->admin)
        ->get(route('admin.vendors.index', ['status' => 'suspended']))
        ->assertStatus(200)
        ->assertSee($suspended->name);
});

it('shows vendor detail page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.vendors.show', $this->vendor))
        ->assertStatus(200)
        ->assertViewIs('pages.admin.vendors.show')
        ->assertSee($this->vendor->name);
});

it('can toggle vendor status', function () {
    expect($this->vendor->is_active)->toBeTruthy();

    $this->actingAs($this->admin)
        ->post(route('admin.vendors.toggle-status', $this->vendor))
        ->assertRedirect();

    $this->vendor->refresh();
    expect($this->vendor->is_active)->toBeFalsy();

    // Toggle back
    $this->actingAs($this->admin)
        ->post(route('admin.vendors.toggle-status', $this->vendor))
        ->assertRedirect();

    $this->vendor->refresh();
    expect($this->vendor->is_active)->toBeTruthy();
});

it('can update vendor commission rate', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.vendors.commission.update', $this->vendor), ['rate' => 12.5])
        ->assertRedirect()
        ->assertSessionHas('success');

    $setting = CommissionSetting::where('vendor_id', $this->vendor->id)->first();
    expect((float) $setting->rate)->toBe(12.5);
});

it('validates commission rate', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.vendors.commission.update', $this->vendor), ['rate' => 60])
        ->assertSessionHasErrors('rate');
});

it('denies access to non-admin', function () {
    $customer = User::factory()->create(['role_id' => 3]);

    $this->actingAs($customer)
        ->get(route('admin.vendors.index'))
        ->assertStatus(403);
});

it('returns 404 for non-vendor user on show', function () {
    $customer = User::factory()->create(['role_id' => 3]);

    $this->actingAs($this->admin)
        ->get(route('admin.vendors.show', $customer))
        ->assertStatus(404);
});

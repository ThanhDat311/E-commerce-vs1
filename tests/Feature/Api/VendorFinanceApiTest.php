<?php

/** @var \Tests\TestCase $this */

use App\Models\Commission;
use App\Models\Order;
use App\Models\User;


beforeEach(function () {
    $this->vendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);
    $this->otherVendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);
});

it('vendor can view their finance summary', function () {
    Commission::factory()->create([
        'vendor_id' => $this->vendor->id,
        'order_id' => Order::factory()->create()->id,
        'order_total' => 200.00,
        'commission_rate' => 10,
        'commission_amount' => 20.00,
        'status' => 'pending',
    ]);

    $this->actingAs($this->vendor);

    $response = $this->getJson('/api/v1/vendor/finance');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'summary' => [
                    'total_earned',
                    'total_commission',
                    'net_payout',
                    'pending_payout',
                    'paid_commission',
                ],
                'commissions',
                'pagination',
            ],
        ])
        ->assertJsonPath('data.summary.total_earned', 200)
        ->assertJsonPath('data.summary.total_commission', 20)
        ->assertJsonPath('data.summary.pending_payout', 20);
});

it('vendor only sees their own commissions, not others', function () {
    $myCommission = Commission::factory()->create([
        'vendor_id' => $this->vendor->id,
        'order_id' => Order::factory()->create()->id,
        'order_total' => 100.00,
        'commission_amount' => 10.00,
        'status' => 'paid',
    ]);

    $otherCommission = Commission::factory()->create([
        'vendor_id' => $this->otherVendor->id,
        'order_id' => Order::factory()->create()->id,
        'order_total' => 500.00,
        'commission_amount' => 50.00,
        'status' => 'paid',
    ]);

    $this->actingAs($this->vendor);

    $response = $this->getJson('/api/v1/vendor/finance');

    $response->assertOk()
        ->assertJsonPath('data.summary.total_earned', 100)
        ->assertJsonMissing(['order_total' => 500.00]);
});

it('unauthenticated user cannot access vendor finance API', function () {
    $this->getJson('/api/v1/vendor/finance')->assertUnauthorized();
});

it('customer cannot access vendor finance API', function () {
    $customer = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $this->actingAs($customer);

    $this->getJson('/api/v1/vendor/finance')->assertForbidden();
});

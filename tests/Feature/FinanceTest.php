<?php

use App\Models\Commission;
use App\Models\CommissionSetting;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/** @var Tests\TestCase $this */

test('admin can view finance dashboard', function () {
    $admin = User::factory()->create(['role_id' => 1]);

    // Seed global commission setting
    CommissionSetting::factory()->global()->create();

    $response = $this->actingAs($admin)->get(route('admin.finance.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.finance.index');
    $response->assertViewHas([
        'totalRevenue',
        'platformCommissions',
        'pendingPayouts',
        'taxCollected',
        'chartLabels',
        'chartRevenue',
        'chartPayouts',
        'globalRate',
        'transactions',
    ]);
});

test('admin can update global commission rate', function () {
    $admin = User::factory()->create(['role_id' => 1]);

    $response = $this->actingAs($admin)
        ->post(route('admin.finance.commission.update'), [
            'rate' => 10.00,
        ]);

    $response->assertRedirect(route('admin.finance.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('commission_settings', [
        'vendor_id' => null,
        'rate' => '10.00',
    ]);
});

test('commission rate validation rejects invalid values', function () {
    $admin = User::factory()->create(['role_id' => 1]);

    $response = $this->actingAs($admin)
        ->post(route('admin.finance.commission.update'), [
            'rate' => 55,
        ]);

    $response->assertSessionHasErrors('rate');
});

test('admin can export finance report as CSV', function () {
    $admin = User::factory()->create(['role_id' => 1]);

    $response = $this->actingAs($admin)->get(route('admin.finance.export'));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

test('non-admin cannot access finance dashboard', function () {
    $customer = User::factory()->create(['role_id' => 3]);

    $response = $this->actingAs($customer)->get(route('admin.finance.index'));

    $response->assertStatus(403);
});

test('finance dashboard displays transactions', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    CommissionSetting::factory()->global()->create();

    $order = Order::create([
        'user_id' => $admin->id,
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '0123456789',
        'address' => '123 Test St',
        'payment_status' => 'paid',
        'total' => 100,
    ]);

    Commission::create([
        'order_id' => $order->id,
        'order_total' => 100,
        'commission_rate' => 8.50,
        'commission_amount' => 8.50,
        'status' => 'paid',
        'paid_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.finance.index'));

    $response->assertStatus(200);
    $response->assertSee('TRX-');
});

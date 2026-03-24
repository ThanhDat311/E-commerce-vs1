<?php

use App\Models\Deal;
use App\Models\Product;
use App\Models\User;
use App\Services\DealService;
use App\Services\PriceCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ────────────────────────────────────────────────────────────────────────────
// DealService – Feature tests (require DB + application)
// ────────────────────────────────────────────────────────────────────────────

test('deal is not active after end date', function () {
    $deal = Deal::factory()->create([
        'status' => 'active',
        'start_date' => now()->subDays(10),
        'end_date' => now()->subDay(),
    ]);

    $service = new DealService;
    expect($service->isDealActive($deal))->toBeFalse();
});

test('deal is not active when status is draft', function () {
    $deal = Deal::factory()->create([
        'status' => 'draft',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);

    $service = new DealService;
    expect($service->isDealActive($deal))->toBeFalse();
});

test('usage limit exceeded makes deal inactive', function () {
    $deal = Deal::factory()->create([
        'status' => 'active',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'usage_limit' => 5,
        'usage_count' => 5,
    ]);

    $service = new DealService;
    expect($service->isDealActive($deal))->toBeFalse();
});

test('higher discount deal wins in applyBestDeal', function () {
    $product = Product::factory()->create(['price' => 100]);

    $lowDeal = Deal::factory()->create([
        'discount_type' => 'percent',
        'discount_value' => 10,
        'apply_scope' => 'global',
        'priority' => 1,
        'vendor_id' => null,
    ]);

    $highDeal = Deal::factory()->create([
        'discount_type' => 'percent',
        'discount_value' => 20,
        'apply_scope' => 'global',
        'priority' => 10,
        'vendor_id' => null,
    ]);

    $calc = new PriceCalculatorService;
    $result = $calc->applyBestDeal($product, \Illuminate\Database\Eloquent\Collection::make([$lowDeal, $highDeal]));

    expect($result['discount_amount'])->toBe(20.0)
        ->and($result['deal']->id)->toBe($highDeal->id);
});

test('expireDeals marks expired active deals as expired', function () {
    $deal = Deal::factory()->create([
        'status' => 'active',
        'start_date' => now()->subDays(5),
        'end_date' => now()->subHour(),
    ]);

    $service = new DealService;
    $count = $service->expireDeals();

    expect($count)->toBeGreaterThanOrEqual(1)
        ->and($deal->fresh()->status)->toBe('expired');
});

// ────────────────────────────────────────────────────────────────────────────
// HTTP tests – Admin CRUDs (role_id: 1=Admin, 2=Staff, 3=Customer, 4=Vendor)
// ────────────────────────────────────────────────────────────────────────────

test('admin can create deal', function () {
    $admin = User::factory()->create(['role_id' => 1, 'is_active' => true]);

    $response = $this->actingAs($admin)->post(route('admin.deals.store'), [
        'name' => 'Test Deal',
        'discount_type' => 'percent',
        'discount_value' => 15,
        'start_date' => now()->addHour()->format('Y-m-d\TH:i'),
        'end_date' => now()->addDays(7)->format('Y-m-d\TH:i'),
        'apply_scope' => 'global',
        'status' => 'active',
    ]);

    $response->assertRedirect(route('admin.deals.index'));
    $this->assertDatabaseHas('deals', ['name' => 'Test Deal', 'discount_value' => 15]);
});

test('admin can approve vendor deal', function () {
    $admin = User::factory()->create(['role_id' => 1, 'is_active' => true]);
    $vendor = User::factory()->create(['role_id' => 4, 'is_active' => true]);

    $deal = Deal::factory()->create([
        'status' => 'pending',
        'vendor_id' => $vendor->id,
        'created_by' => $vendor->id,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.deals.approve', $deal));

    $response->assertRedirect(route('admin.deals.index'));
    expect($deal->fresh()->status)->toBe('active')
        ->and($deal->fresh()->approved_by)->toBe($admin->id);
});

test('vendor cannot edit another vendors deal', function () {
    $vendor1 = User::factory()->create(['role_id' => 4, 'is_active' => true]);
    $vendor2 = User::factory()->create(['role_id' => 4, 'is_active' => true]);

    $deal = Deal::factory()->create([
        'vendor_id' => $vendor1->id,
        'created_by' => $vendor1->id,
        'status' => 'draft',
    ]);

    $response = $this->actingAs($vendor2)->get(route('vendor.deals.edit', $deal));
    $response->assertStatus(403);
});

test('staff cannot access admin deal delete route', function () {
    $staff = User::factory()->create(['role_id' => 2, 'is_active' => true]);
    $deal = Deal::factory()->create();

    // Admin-only route → staff should get 403 from role middleware
    $response = $this->actingAs($staff)->delete(route('admin.deals.destroy', $deal));
    $response->assertStatus(403);
});

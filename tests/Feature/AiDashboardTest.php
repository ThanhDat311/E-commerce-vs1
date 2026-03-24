<?php

use App\Models\AiFeatureStore;
use App\Models\AuthLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('admin can view ai risk dashboard with chart data', function () {
    // Fake the microservice health check
    Http::fake([
        '*/health' => Http::response(['status' => 'ok'], 200),
    ]);

    $admin = User::factory()->create(['role_id' => 1]); // Assuming 1 is admin

    // Create some fake data
    AiFeatureStore::factory()->count(3)->create(['risk_score' => 0.4]);
    AuthLog::factory()->count(2)->create(['risk_score' => 0.7, 'auth_decision' => 'block_access']);

    $response = $this->actingAs($admin)->get(route('admin.ai.dashboard.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.ai-dashboard.index');

    // Assert all chart data is passed to the view
    $response->assertViewHasAll([
        'riskTrendData',
        'donutData',
        'distributionData',
        'hourlyData',
        'totalEvaluations',
        'loginTotal',
        'highRiskEvaluations',
        'aiServiceOnline',
    ]);
});

test('non-admin cannot access ai risk dashboard', function () {
    $customer = User::factory()->create(['role_id' => 3]); // Assuming 3 is customer

    $response = $this->actingAs($customer)->get(route('admin.ai.dashboard.index'));

    $response->assertStatus(403);
});

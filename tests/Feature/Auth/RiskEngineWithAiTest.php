<?php

use App\Services\AiMicroserviceClient;
use App\Services\Auth\RiskEngineService;
use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Http;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helper: Fake AI response ──────────────────────────────────────────────────
function fakeLoginRiskResponse(string $decision, float $score = 0.5): void
{
    Http::fake([
        'localhost:8000/api/v1/predict-login-risk' => Http::response([
            'status' => 'success',
            'data'   => [
                'risk_score'    => $score,
                'auth_decision' => $decision,
                'reasons'       => ['Automated AI Assessment'],
            ],
        ], 200),
    ]);
}

// ── Tests ─────────────────────────────────────────────────────────────────────

test('AI returns challenge_otp → MFA is triggered', function () {
    fakeLoginRiskResponse('challenge_otp', 0.70);

    $user    = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // MFA required

    Http::assertSent(fn($req) => str_contains($req->url(), 'predict-login-risk'));
});

test('AI returns block_access → MFA is triggered (strongest check)', function () {
    fakeLoginRiskResponse('block_access', 0.95);

    $user    = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // Blocked → MFA step
});

test('AI returns passive_auth_allow → login allowed without MFA', function () {
    fakeLoginRiskResponse('passive_auth_allow', 0.10);

    $user    = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeFalse(); // No MFA needed
});

test('trusted device bypasses AI entirely', function () {
    // AI should NOT be called if device is trusted
    Http::fake(); // no route set → would fail if called

    $user     = User::factory()->create(['is_active' => true]);
    $deviceId = 'trusted-device-uuid';

    LoginHistory::create([
        'user_id'    => $user->id,
        'device_id'  => $deviceId,
        'ip_address' => '127.0.0.1',
    ]);

    $request = \Illuminate\Http\Request::create('/login', 'POST');
    $request->cookies->set(RiskEngineService::DEVICE_COOKIE_NAME, $deviceId);

    $service = app(RiskEngineService::class);
    $result  = $service->evaluate($user, $request);

    expect($result)->toBeFalse();

    // Verify AI was never contacted
    Http::assertNothingSent();
});

test('AI offline → fallback to legacy IP check (new IP triggers MFA)', function () {
    // Simulate AI service being down (connection refused)
    Http::fake([
        'localhost:8000/api/v1/predict-login-risk' => Http::response([], 500),
    ]);

    $user    = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    // No prior login history → should trigger MFA via fallback
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // No known IP → MFA via legacy check
});

test('AI offline → fallback allows known IP', function () {
    Http::fake([
        'localhost:8000/api/v1/predict-login-risk' => Http::response([], 500),
    ]);

    $user = User::factory()->create(['is_active' => true]);

    // Seed a login history from 127.0.0.1 (the request IP in tests)
    LoginHistory::create([
        'user_id'    => $user->id,
        'ip_address' => '127.0.0.1',
        'device_id'  => 'some-device',
    ]);

    $service = app(RiskEngineService::class);
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeFalse(); // Known IP → no MFA needed
});

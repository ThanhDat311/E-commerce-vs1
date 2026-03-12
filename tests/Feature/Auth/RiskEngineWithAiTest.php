<?php

use App\Models\AuthLog;
use App\Models\LoginHistory;
use App\Models\User;
use App\Services\Auth\RiskEngineService;
use Illuminate\Support\Facades\Http;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helper: Fake AI response ──────────────────────────────────────────────────
function fakeLoginRiskResponse(string $decision, float $score = 0.5, string $riskLevel = 'medium'): void
{
    Http::fake([
        '*/api/v1/predict-login-risk' => Http::response([
            'status' => 'success',
            'data' => [
                'risk_score' => $score,
                'risk_level' => $riskLevel,
                'auth_decision' => $decision,
                'reasons' => ['Automated AI Assessment'],
            ],
        ], 200),
    ]);
}

// ── Tests ─────────────────────────────────────────────────────────────────────

test('AI returns challenge_otp → MFA is triggered', function () {
    fakeLoginRiskResponse('challenge_otp', 0.70);

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // MFA required

    Http::assertSent(fn ($req) => str_contains($req->url(), 'predict-login-risk'));
});

test('AI returns block_access → MFA is triggered (strongest check)', function () {
    fakeLoginRiskResponse('block_access', 0.95);

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // Blocked → MFA step
});

test('AI returns passive_auth_allow → login allowed without MFA', function () {
    fakeLoginRiskResponse('passive_auth_allow', 0.10);

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeFalse(); // No MFA needed
});

test('trusted device bypasses AI entirely', function () {
    // AI should NOT be called if device is trusted
    Http::fake(); // no route set → would fail if called

    $user = User::factory()->create(['is_active' => true]);
    $deviceId = 'trusted-device-uuid';

    LoginHistory::create([
        'user_id' => $user->id,
        'device_id' => $deviceId,
        'ip_address' => '127.0.0.1',
    ]);

    $request = \Illuminate\Http\Request::create('/login', 'POST');
    $request->cookies->set(RiskEngineService::DEVICE_COOKIE_NAME, $deviceId);

    $service = app(RiskEngineService::class);
    $result = $service->evaluate($user, $request);

    expect($result)->toBeFalse();

    // Verify AI was never contacted
    Http::assertNothingSent();
});

test('AI offline → fallback to legacy IP check (new IP triggers MFA)', function () {
    // Simulate AI service being down (connection refused)
    Http::fake([
        '*/api/v1/predict-login-risk' => Http::response([], 500),
    ]);

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);

    // No prior login history → should trigger MFA via fallback
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // No known IP → MFA via legacy check
});

test('AI offline → fallback allows known IP', function () {
    Http::fake([
        '*/api/v1/predict-login-risk' => Http::response([], 500),
    ]);

    $user = User::factory()->create(['is_active' => true]);

    // Seed a login history from 127.0.0.1 (the request IP in tests)
    LoginHistory::create([
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'device_id' => 'some-device',
    ]);

    $service = app(RiskEngineService::class);
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeFalse(); // Known IP → no MFA needed
});

// ── Score-based validation tests ──────────────────────────────────────────────

test('low risk score overrides AI to passive_auth_allow with correct risk_level', function () {
    // AI may return wrong decision, but our service should override based on score
    fakeLoginRiskResponse('challenge_otp', 0.15, 'high');

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeFalse(); // Low score → should allow

    $log = AuthLog::latest()->first();
    expect($log->risk_level)->toBe('low');
    expect($log->auth_decision)->toBe('passive_auth_allow');
    expect($log->is_successful)->toBeTrue();
});

test('medium risk score overrides AI to challenge_otp', function () {
    fakeLoginRiskResponse('passive_auth_allow', 0.45, 'low');

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // Medium score → should challenge

    $log = AuthLog::latest()->first();
    expect($log->risk_level)->toBe('medium');
    expect($log->auth_decision)->toBe('challenge_otp');
    expect($log->is_successful)->toBeFalse();
});

test('critical risk score overrides AI to block_access', function () {
    fakeLoginRiskResponse('passive_auth_allow', 0.92, 'low');

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $result = $service->evaluate($user, $request);

    expect($result)->toBeTrue(); // Critical score → should block

    $log = AuthLog::latest()->first();
    expect($log->risk_level)->toBe('critical');
    expect($log->auth_decision)->toBe('block_access');
    expect($log->is_successful)->toBeFalse();
});

test('challenge decisions set is_successful to false', function () {
    fakeLoginRiskResponse('challenge_biometric', 0.65, 'high');

    $user = User::factory()->create(['is_active' => true]);
    $service = app(RiskEngineService::class);
    $request = \Illuminate\Http\Request::create('/login', 'POST');

    $service->evaluate($user, $request);

    $log = AuthLog::latest()->first();
    expect($log->is_successful)->toBeFalse();
    expect($log->auth_decision)->toBe('challenge_biometric');
    expect($log->risk_level)->toBe('high');
});

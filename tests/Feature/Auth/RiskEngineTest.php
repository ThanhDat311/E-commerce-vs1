<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('unrecognized device triggers MFA', function () {
    $user = User::factory()->create();
    Http::fake(['*/api/v1/predict-login-risk' => Http::response(['data' => ['risk_score' => 0.4, 'auth_decision' => 'challenge_otp']], 200)]);
    Mail::fake();
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('auth.mfa.show'));
});

test('trusted device bypasses MFA', function () {
    $user = User::factory()->create();
    $deviceId = 'trusted-123';
    \App\Models\LoginHistory::create(['user_id' => $user->id, 'device_id' => $deviceId, 'ip_address' => '127.0.0.1']);
    $this->withCookie(\App\Services\Auth\RiskEngineService::DEVICE_COOKIE_NAME, $deviceId)
        ->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('home'));
});

test('TC-MFA-01: Low risk login bypasses MFA', function () {
    $user = User::factory()->create();
    Http::fake(['*/api/v1/predict-login-risk' => Http::response(['data' => ['risk_score' => 0.1, 'auth_decision' => 'passive_auth_allow']], 200)]);
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('home'));
});

test('logins from whitelisted IPs bypass AI evaluation', function () {
    $user = User::factory()->create();
    \App\Models\RiskList::create(['type' => 'ip', 'value' => '1.2.3.4', 'action' => 'whitelist']);
    Http::fake();
    $this->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('home'));
    Http::assertNotSent(fn ($r) => str_contains($r->url(), 'predict-login-risk'));
});

test('logins from blacklisted IPs are blocked immediately', function () {
    $user = User::factory()->create();
    \App\Models\RiskList::create(['type' => 'ip', 'value' => '9.9.9.9', 'action' => 'block']);
    $this->withServerVariables(['REMOTE_ADDR' => '9.9.9.9'])->post('/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('specific users on whitelist bypass MFA', function () {
    $user = User::factory()->create();
    \App\Models\RiskList::create(['type' => 'user_id', 'value' => (string) $user->id, 'action' => 'whitelist']);
    Http::fake();
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('home'));
    Http::assertNotSent(fn ($r) => str_contains($r->url(), 'predict-login-risk'));
});

test('device type "mobile" is detected and sent to AI', function () {
    $user = User::factory()->create();
    Http::fake(['*/api/v1/predict-login-risk' => Http::response(['data' => ['risk_score' => 0.1, 'auth_decision' => 'passive_auth_allow']], 200)]);
    $this->withHeader('User-Agent', 'iPhone')->post('/login', ['email' => $user->email, 'password' => 'password']);
    Http::assertSent(fn ($r) => ($r['device_type'] ?? '') === 'mobile');
});

test('system falls back to MFA when AI service is unavailable', function () {
    $user = User::factory()->create();
    Http::fake(['*/api/v1/predict-login-risk' => Http::response([], 500)]);
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('auth.mfa.show'));
});

test('AI: high risk score triggers challenge_biometric', function () {
    $user = User::factory()->create();
    Http::fake(['*/api/v1/predict-login-risk' => Http::response(['data' => ['risk_score' => 0.7, 'auth_decision' => 'challenge_biometric']], 200)]);
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('auth.mfa.show'));
});

test('AI: critical risk score triggers block_access', function () {
    $user = User::factory()->create();
    Http::fake(['*/api/v1/predict-login-risk' => Http::response(['data' => ['risk_score' => 0.9, 'auth_decision' => 'block_access']], 200)]);
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
});

test('Location: login from new location triggers MFA (fallback)', function () {
    $user = User::factory()->create();
    Http::fake(['*/api/v1/predict-login-risk' => Http::response(null, 500)]); // Fallback to legacy
    // Legacy fallback check login histories
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('auth.mfa.show'));
});

test('Location: known location bypasses MFA (fallback)', function () {
    $user = User::factory()->create();
    \App\Models\LoginHistory::create(['user_id' => $user->id, 'device_id' => 'dev1', 'ip_address' => '127.0.0.1']);
    Http::fake(['*/api/v1/predict-login-risk' => Http::response(null, 500)]); // Fallback to legacy
    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('home'));
});

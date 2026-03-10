<?php

use App\Models\User;
use App\Models\LoginHistory;
use App\Services\Auth\RiskEngineService;
use Illuminate\Support\Facades\Crypt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('login without trusted device triggers mfa', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('auth.mfa.show'));
    $this->assertGuest();
});

test('login with trusted device bypasses mfa', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
        'is_active' => true,
    ]);

    $deviceId = 'test-device-uuid';

    // Seed a trusted login history
    LoginHistory::create([
        'user_id' => $user->id,
        'device_id' => $deviceId,
        'ip_address' => '127.0.0.1',
    ]);

    // Send the encrypted cookie
    $response = $this->withCookie(
        RiskEngineService::DEVICE_COOKIE_NAME,
        $deviceId
    )->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('successful mfa verification grants trusted device cookie', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
        'is_active' => true,
        'mfa_secret' => '123456',
        'mfa_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession([
        'mfa_user_id' => $user->id,
    ])->post('/login/mfa', [
        'mfa_code' => '123456',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
    $response->assertCookie(RiskEngineService::DEVICE_COOKIE_NAME);

    // Ensure login history was recorded
    $this->assertDatabaseHas('login_histories', [
        'user_id' => $user->id,
    ]);
});

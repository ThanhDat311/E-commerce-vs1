<?php

use App\Models\User;
use App\Services\Auth\RiskEngineService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

/**
 * ── AUTH & MFA TESTS (Target: 15) ─────────────────────────────────────────────
 */
test('login screen can be rendered', function () {
    $this->get('/login')->assertStatus(200);
});

test('users can authenticate (no risk)', function () {
    $user = User::factory()->create();
    $this->mock(RiskEngineService::class, function ($mock) {
        $mock->shouldReceive('evaluate')->andReturn(false);
        $mock->shouldReceive('recordSuccessfulLogin')->andReturn('mock-device-id');
    });

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home'));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();
    $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post('/logout')->assertRedirect('/');
    $this->assertGuest();
});

test('login requires email and password', function () {
    $this->post('/login', ['email' => '', 'password' => ''])->assertSessionHasErrors(['email', 'password']);
});

test('login fails with non-existent email', function () {
    $this->post('/login', ['email' => 'no@ex.com', 'password' => 'pw'])->assertSessionHasErrors('email');
});

test('inactive users cannot authenticate', function () {
    $user = User::factory()->create(['is_active' => false]);
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
});

test('session is regenerated after login', function () {
    $user = User::factory()->create();
    $oldId = session()->getId();
    $this->mock(RiskEngineService::class, fn ($m) => $m->shouldReceive('evaluate')->andReturn(false)->shouldReceive('recordSuccessfulLogin'));
    $this->post('/login', ['email' => $user->email, 'password' => 'password']);
    expect(session()->getId())->not->toBe($oldId);
});

test('admin is redirected to admin dashboard', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    $this->mock(RiskEngineService::class, fn ($m) => $m->shouldReceive('evaluate')->andReturn(false)->shouldReceive('recordSuccessfulLogin'));
    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect(route('admin.dashboard'));
});

test('vendor is redirected to vendor dashboard', function () {
    $vendor = User::factory()->create(['role_id' => 4]);
    $this->mock(RiskEngineService::class, fn ($m) => $m->shouldReceive('evaluate')->andReturn(false)->shouldReceive('recordSuccessfulLogin'));
    $this->post('/login', ['email' => $vendor->email, 'password' => 'password'])->assertRedirect(route('vendor.dashboard'));
});

test('rate limiting blocks multiple failed attempts (lockout)', function () {
    $user = User::factory()->create();
    $key = Str::lower($user->email).'|127.0.0.1';
    for ($i = 0; $i < 5; $i++) {
        RateLimiter::hit($key);
    }
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
});

test('MFA: medium risk login triggers OTP flow', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);
    $this->mock(RiskEngineService::class, function ($mock) {
        $mock->shouldReceive('evaluate')->andReturn(true); // Trigger MFA
    });
    \Illuminate\Support\Facades\Mail::fake();

    $response = $this->post('/login', ['email' => $user->email, 'password' => 'password']);
    $response->assertRedirect(route('auth.mfa.show'));
    $this->assertGuest();
});

test('MFA: successful OTP verification grants access', function () {
    $user = User::factory()->create(['mfa_secret' => '123456', 'mfa_expires_at' => now()->addMinutes(10)]);
    $response = $this->withSession(['mfa_user_id' => $user->id])->post('/login/mfa', ['mfa_code' => '123456']);
    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('Re-auth: sensitive info change requires password confirmation', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);
    $this->actingAs($user)->get('/confirm-password')->assertStatus(200);
    $this->actingAs($user)->post('/confirm-password', ['password' => 'password'])->assertRedirect();
});

test('registration and forgot password screens can be rendered', function () {
    $this->get('/register')->assertStatus(200);
    $this->get('/forgot-password')->assertStatus(200);
});

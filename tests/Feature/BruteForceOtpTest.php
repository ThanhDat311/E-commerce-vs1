<?php

use App\Models\AuthLog;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user is forced to OTP after 5 failed login attempts', function () {
    // 1. Create a test user
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    // 2. Simulate 5 failed login attempts
    for ($i = 0; $i < 5; $i++) {
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // Verify exactly 5 failed AuthLogs were created via the LogFailedLoginAttempt listener
    expect(AuthLog::where('user_id', $user->id)
        ->where('is_successful', false)
        ->count())->toBe(5);

    // 3. Attempt a successful login on the 6th try
    // We mock the AI microservice to safely return 'passive_auth_allow' (low risk)
    // so we can prove that the LOCAL Velocity rule overrides it to 'challenge_otp'
    $this->mock(\App\Services\AiMicroserviceClient::class, function ($mock) {
        $mock->shouldReceive('predictLoginRisk')
            ->andReturn([
                'risk_score' => 0.01,
                'auth_decision' => 'passive_auth_allow',
                'reasons' => ['Mocked low-risk AI result'],
            ]);
    });

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    // 4. Assert the user is temporarily logged out and redirected to MFA
    $response->assertRedirect(route('auth.mfa.show'));
    $this->assertGuest();

    // Verify the AuthLog correctly recorded the OTP Challenge
    $challengeLog = AuthLog::where('user_id', $user->id)
        ->orderBy('id', 'desc')
        ->first();

    expect($challengeLog->auth_decision)->toBe('challenge_otp')
        ->and($challengeLog->reasons)->toContain('Too many failed login attempts – OTP required');
});

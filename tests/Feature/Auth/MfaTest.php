<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\withSession;

beforeEach(function () {
    $this->user = User::factory()->create([
        'mfa_secret' => '123456',
        'mfa_expires_at' => now()->addMinutes(10),
    ]);
});

test('mfa verify requires correct code', function () {
    withSession(['mfa_user_id' => $this->user->id])
        ->post(route('auth.mfa.verify'), ['mfa_code' => '000000'])
        ->assertSessionHasErrors(['mfa_code' => 'Invalid MFA code.']);
});

test('mfa verify logs out user after 5 failed attempts', function () {
    // Attempt 1 to 4
    for ($i = 0; $i < 4; $i++) {
        withSession(['mfa_user_id' => $this->user->id, 'mfa_failed_attempts' => $i])
            ->post(route('auth.mfa.verify'), ['mfa_code' => '000000']);
    }

    // Attempt 5 -> triggers lockout
    $response = withSession(['mfa_user_id' => $this->user->id, 'mfa_failed_attempts' => 4])
        ->post(route('auth.mfa.verify'), ['mfa_code' => '000000']);

    $response->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->user->refresh();
    expect($this->user->mfa_secret)->toBeNull();
});

test('mfa resend generates new code and sends email', function () {
    Mail::fake();

    withSession(['mfa_user_id' => $this->user->id])
        ->post(route('auth.mfa.resend'))
        ->assertSessionHas('status', 'A new MFA code has been sent to your email.');

    Mail::assertSent(\App\Mail\Auth\MfaCodeMail::class, function ($mail) {
        return $mail->hasTo($this->user->email);
    });

    $this->user->refresh();
    expect($this->user->mfa_secret)->not->toBe('123456');
});

<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('account security page is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/account-security');

    $response->assertOk();
    $response->assertViewIs('profile.security.index');
    $response->assertViewHas('loginHistory');
});

test('guest cannot access account security page', function () {
    $response = $this->get('/account-security');

    $response->assertRedirect('/login');
});

test('user can change password with correct current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $response = $this->actingAs($user)->post('/account-security/password', [
        'current_password' => 'old-password',
        'password' => 'new-secure-password',
        'password_confirmation' => 'new-secure-password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    expect(Hash::check('new-secure-password', $user->password))->toBeTrue();
});

test('password change fails with incorrect current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);

    $response = $this->actingAs($user)
        ->from('/account-security')
        ->post('/account-security/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

    $response->assertRedirect('/account-security');
    $response->assertSessionHasErrors('current_password');
});

test('password change fails when confirmation does not match', function () {
    $user = User::factory()->create([
        'password' => Hash::make('current-password'),
    ]);

    $response = $this->actingAs($user)
        ->from('/account-security')
        ->post('/account-security/password', [
            'current_password' => 'current-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'different-password',
        ]);

    $response->assertRedirect('/account-security');
    $response->assertSessionHasErrors('password');
});

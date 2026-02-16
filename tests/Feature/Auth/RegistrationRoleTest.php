<?php

use App\Models\User;
use App\Models\Role;

test('new users are assigned customer role', function () {
    $response = $this->post('/register', [
        'name' => 'Test Customer',
        'email' => 'customer@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // $this->assertAuthenticated(); // User is not logged in after registration
    $response->assertRedirect(route('login', absolute: false));

    $user = User::where('email', 'customer@example.com')->first();

    expect($user->role_id)->toBe(3);
});

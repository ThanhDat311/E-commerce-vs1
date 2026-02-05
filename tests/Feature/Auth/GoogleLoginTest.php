<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Mockery;

test('redirects to google', function () {
    $response = $this->get(route('auth.google'));

    $response->assertRedirectContains('accounts.google.com');
});

test('creates a new user and logs them in via google callback', function () {
    // Mock the Socialite User
    $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
    $abstractUser
        ->shouldReceive('getId')
        ->andReturn('123456789')
        ->shouldReceive('getName')
        ->andReturn('Google User')
        ->shouldReceive('getEmail')
        ->andReturn('google-new@example.com')
        ->shouldReceive('getAvatar')
        ->andReturn('https://via.placeholder.com/150');

    // Mock the Socialite Driver
    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($driver = Mockery::mock('Laravel\Socialite\Two\AbstractProvider'));

    $driver->shouldReceive('user')
        ->andReturn($abstractUser);

    // Call the callback route
    $response = $this->get(route('auth.google.callback'));

    // Assert redirection to home
    $response->assertRedirect(route('home'));

    // Assert User was created
    $this->assertDatabaseHas('users', [
        'email' => 'google-new@example.com',
        'google_id' => '123456789',
        'role_id' => 3, // Customer
    ]);

    // Assert User is authenticated
    $this->assertAuthenticated();
});

test('logs in an existing user via google callback', function () {
    // Create an existing user
    $user = User::factory()->create([
        'email' => 'existing@example.com',
        'google_id' => null, // Simulate user created via normal registration initially
        'role_id' => 3,
    ]);

    // Mock the Socialite User
    $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
    $abstractUser
        ->shouldReceive('getId')
        ->andReturn('987654321')
        ->shouldReceive('getName')
        ->andReturn('Existing User')
        ->shouldReceive('getEmail')
        ->andReturn('existing@example.com'); // Matches existing email

    // Mock the Socialite Driver
    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($driver = Mockery::mock('Laravel\Socialite\Two\AbstractProvider'));

    $driver->shouldReceive('user')
        ->andReturn($abstractUser);

    // Call the callback route
    $response = $this->get(route('auth.google.callback'));

    // Assert redirection to home
    $response->assertRedirect(route('home'));

    // Assert DB was updated with google_id
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'google_id' => '987654321',
    ]);

    // Assert Authenticated as the correct user
    $this->assertAuthenticatedAs($user);
});

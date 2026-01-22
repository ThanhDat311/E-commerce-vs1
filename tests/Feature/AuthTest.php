<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test user registration process.
     * This test verifies that a new user can register successfully
     * and the data is stored in the database.
     */
    public function test_user_can_register_successfully()
    {
        // Prepare registration data
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send POST request to registration route
        $response = $this->post(route('register'), $userData);

        // Assert successful registration (redirect to login page)
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        // Assert user is created in database
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        // Note: User is not automatically authenticated after registration
        // They need to login separately
        // $this->assertAuthenticated();

        // Verify password is hashed
        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test user login process.
     * This test verifies that an existing user can login successfully.
     */
    public function test_user_can_login_successfully()
    {
        // Create a user in the database
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Prepare login data
        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        // Send POST request to login route
        $response = $this->post(route('login'), $loginData);

        // Assert successful login (redirect to home page)
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));

        // Assert user is authenticated
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login with invalid credentials.
     * This test verifies that login fails with wrong password.
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        // Create a user in the database
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Prepare invalid login data
        $loginData = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];

        // Send POST request to login route
        $response = $this->post(route('login'), $loginData);

        // Assert login fails (redirect back with errors)
        $response->assertStatus(302);
        $response->assertRedirect('/');

        // Assert user is not authenticated
        $this->assertGuest();

        // Assert error message is present
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test registration with duplicate email.
     * This test verifies that registration fails when email already exists.
     */
    public function test_user_cannot_register_with_duplicate_email()
    {
        // Create a user in the database
        $existingUser = User::factory()->create();

        // Prepare registration data with existing email
        $userData = [
            'name' => $this->faker->name,
            'email' => $existingUser->email, // Duplicate email
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send POST request to registration route
        $response = $this->post(route('register'), $userData);

        // Assert registration fails (redirect back with errors)
        $response->assertStatus(302);
        $response->assertRedirect('/');

        // Assert user is not authenticated
        $this->assertGuest();

        // Assert error message is present
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test registration with invalid data.
     * This test verifies that registration fails with missing required fields.
     */
    public function test_user_cannot_register_with_invalid_data()
    {
        // Prepare invalid registration data (missing name)
        $userData = [
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // Missing 'name' field
        ];

        // Send POST request to registration route
        $response = $this->post(route('register'), $userData);

        // Assert registration fails (redirect back with errors)
        $response->assertStatus(302);
        $response->assertRedirect('/');

        // Assert user is not authenticated
        $this->assertGuest();

        // Assert error message is present
        $response->assertSessionHasErrors('name');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to login with Google. Please try again.']);
        }

        // 1. Check if user exists by google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            Auth::login($user);
            return redirect()->intended(route('home'));
        }

        // 2. Check if user exists by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update google_id for existing user
            $user->update([
                'google_id' => $googleUser->getId(),
            ]);

            Auth::login($user);
            return redirect()->intended(route('home'));
        }

        // 3. Create new user
        $newUser = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => Hash::make(Str::random(16)), // Random secure password
            'role_id' => 3, // Customer
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verify email from Google
        ]);

        Auth::login($newUser);

        return redirect()->intended(route('home'));
    }
}

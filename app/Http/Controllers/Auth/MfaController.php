<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MfaController extends Controller
{
    public function show(Request $request)
    {
        if (! $request->session()->has('mfa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.mfa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'mfa_code' => 'required|string|size:6',
        ]);

        $userId = $request->session()->get('mfa_user_id');

        if (! $userId) {
            return redirect()->route('login')->withErrors(['email' => 'MFA session expired. Please log in again.']);
        }

        $user = \App\Models\User::find($userId);

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        if (! $user->mfa_secret || $user->mfa_secret !== $request->mfa_code) {
            return back()->withErrors(['mfa_code' => 'Invalid MFA code.']);
        }

        if (now()->greaterThan($user->mfa_expires_at)) {
            return back()->withErrors(['mfa_code' => 'MFA code has expired. Please log in again to receive a new code.']);
        }

        // Success - clear MFA data and log the user in
        $user->mfa_secret = null;
        $user->mfa_expires_at = null;
        $user->save();

        Auth::login($user);
        $request->session()->forget('mfa_user_id');
        $request->session()->regenerate();

        // Role-based redirect
        $redirectRoute = match ($user->role_name) {
            'admin' => 'admin.dashboard',
            'staff' => 'staff.dashboard',
            'vendor' => 'vendor.dashboard',
            'customer' => 'home',
            default => 'home',
        };

        return redirect()->intended(route($redirectRoute));
    }
}

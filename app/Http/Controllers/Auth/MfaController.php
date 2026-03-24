<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyMfaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MfaController extends Controller
{
    public function show(Request $request)
    {
        if (! $request->session()->has('mfa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.mfa');
    }

    public function verify(VerifyMfaRequest $request)
    {
        $userId = $request->session()->get('mfa_user_id');

        if (! $userId) {
            return redirect()->route('login')->withErrors(['email' => 'MFA session expired. Please log in again.']);
        }

        $user = \App\Models\User::find($userId);

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        if (! $user->mfa_secret || $user->mfa_secret !== $request->validated('mfa_code')) {
            $attempts = $request->session()->get('mfa_failed_attempts', 0) + 1;
            $request->session()->put('mfa_failed_attempts', $attempts);

            if ($attempts >= 5) {
                // Lockout
                $user->mfa_secret = null;
                $user->mfa_expires_at = null;
                $user->save();

                $request->session()->forget(['mfa_user_id', 'mfa_failed_attempts']);

                return redirect()->route('login')->withErrors(['email' => 'Too many invalid attempts. MFA session invalidated, please log in again.']);
            }

            return back()->withErrors(['mfa_code' => 'Invalid MFA code.']);
        }

        if (now()->greaterThan($user->mfa_expires_at)) {
            return back()->withErrors(['mfa_code' => 'MFA code has expired. Please log in again or request a new code.']);
        }

        // Success - clear MFA data and log the user in
        $user->mfa_secret = null;
        $user->mfa_expires_at = null;
        $user->save();

        Auth::login($user);
        $request->session()->forget(['mfa_user_id', 'mfa_failed_attempts']);
        $request->session()->regenerate();

        // Record successful login history and grant device trust after passing MFA
        $riskEngine = app(\App\Services\Auth\RiskEngineService::class);
        $deviceId = $riskEngine->recordSuccessfulLogin($user, $request);
        cookie()->queue(cookie()->forever(\App\Services\Auth\RiskEngineService::DEVICE_COOKIE_NAME, $deviceId));

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

    public function resend(Request $request)
    {
        $userId = $request->session()->get('mfa_user_id');

        if (! $userId) {
            return redirect()->route('login')->withErrors(['email' => 'MFA session expired. Please log in again.']);
        }

        $user = \App\Models\User::find($userId);

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        // Generate new code
        $mfaCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->mfa_secret = $mfaCode;
        $user->mfa_expires_at = now()->addMinutes(10);
        $user->save();

        // Dispatch email
        Mail::to($user->email)->send(new \App\Mail\Auth\MfaCodeMail($mfaCode));

        return back()->with('status', 'A new MFA code has been sent to your email.');
    }
}

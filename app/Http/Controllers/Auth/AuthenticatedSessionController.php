<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Check if account is active
        if (! $user->is_active) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.',
            ]);
        }

        // Use RiskEngineService to evaluate login risk
        $riskEngine = app(\App\Services\Auth\RiskEngineService::class);
        $requiresMfa = $riskEngine->evaluate($user, $request);

        if ($requiresMfa) {
            $mfaCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->mfa_secret = $mfaCode;
            $user->mfa_expires_at = now()->addMinutes(10);
            $user->save();

            // Dispatch Mailable
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\Auth\MfaCodeMail($mfaCode));

            // Log out the user temporarily and put them in a pending MFA state using Session
            Auth::logout();
            $request->session()->put('mfa_user_id', $user->id);

            return redirect()->route('auth.mfa.show');
        }

        // Record successful login history without MFA
        $deviceId = $riskEngine->recordSuccessfulLogin($user, $request);

        // Attach trusted device cookie
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

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

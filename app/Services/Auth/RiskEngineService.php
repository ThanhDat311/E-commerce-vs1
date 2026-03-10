<?php

namespace App\Services\Auth;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class RiskEngineService
{
    public const DEVICE_COOKIE_NAME = 'mfa_trusted_device';

    /**
     * Evaluate if the current login attempt is risky.
     * Returns true if MFA should be triggered.
     */
    public function evaluate(User $user, Request $request): bool
    {
        // 1. Check Device Trust
        $deviceId = $request->cookie(self::DEVICE_COOKIE_NAME);
        $isDeviceTrusted = false;

        if ($deviceId) {
            $isDeviceTrusted = LoginHistory::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->exists();
        }

        // If device is trusted, we consider the login safe.
        // In a true hybrid system, we might still check the IP, but for now, device trumps.
        if ($isDeviceTrusted) {
            return false;
        }

        // 2. Check IP Location History
        $ip = $request->ip();

        // Use generic testing IP if local, otherwise we get no location data
        if ($ip === '127.0.0.1' || $ip === '::1') {
            // We can optionally mock an IP for local testing, e.g.
            // $ip = '8.8.8.8'; 
            // But for now we just allow localhost to trigger MFA if device isn't trusted.
        }

        $position = Location::get($ip);

        $locationName = $position ? ($position->cityName ?: 'Unknown') . ', ' . ($position->countryName ?: 'Unknown') : null;

        $hasLoginFromLocationOrIp = LoginHistory::where('user_id', $user->id)
            ->where(function ($query) use ($ip, $locationName) {
                $query->where('ip_address', $ip);
                if ($locationName) {
                    $query->orWhere('location->name', $locationName);
                }
            })->exists();

        // 3. Decision
        // If they have never logged in from this IP or generalized location AND the device is new
        if (!$hasLoginFromLocationOrIp) {
            return true; // Trigger MFA
        }

        return false;
    }

    /**
     * Record a successful login and return a new or existing Device ID.
     */
    public function recordSuccessfulLogin(User $user, Request $request): string
    {
        $deviceId = $request->cookie(self::DEVICE_COOKIE_NAME) ?: Str::uuid()->toString();
        $ip = $request->ip();
        $position = Location::get($ip);
        $locationName = $position ? ($position->cityName ?: 'Unknown') . ', ' . ($position->countryName ?: 'Unknown') : null;

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'device_id' => $deviceId,
            'location' => [
                'name' => $locationName,
                'raw' => $position ? $position->toArray() : null,
            ],
        ]);

        return $deviceId;
    }
}

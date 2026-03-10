<?php

namespace App\Services\Auth;

use App\Models\LoginHistory;
use App\Models\User;
use App\Services\AiMicroserviceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class RiskEngineService
{
    public const DEVICE_COOKIE_NAME = 'mfa_trusted_device';

    protected AiMicroserviceClient $aiClient;

    public function __construct(AiMicroserviceClient $aiClient)
    {
        $this->aiClient = $aiClient;
    }

    /**
     * Evaluate if the current login attempt is risky.
     * Returns true if MFA should be triggered.
     *
     * Priority:
     *   1. Trusted device cookie → allow immediately (no AI call needed)
     *   2. AI microservice → use its auth_decision
     *   3. Fallback (AI offline) → legacy IP/Location check
     */
    public function evaluate(User $user, Request $request): bool
    {
        // --- 1. Check Device Trust ---
        $deviceId = $request->cookie(self::DEVICE_COOKIE_NAME);
        $isDeviceTrusted = false;

        if ($deviceId) {
            $isDeviceTrusted = LoginHistory::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->exists();
        }

        // Trusted device always bypasses everything (including AI)
        if ($isDeviceTrusted) {
            return false;
        }

        // --- 2. Detect device type from User-Agent ---
        $userAgent  = $request->userAgent() ?? '';
        $deviceType = $this->detectDeviceType($userAgent);

        // --- 3. Try AI Microservice ---
        $ip = $request->ip();
        $aiResult = $this->aiClient->predictLoginRisk(
            userId: $user->id,
            ip: $ip,
            userAgent: $userAgent,
            deviceType: $deviceType,
            country: null, // geolocation lookup done below if needed
        );

        if ($aiResult !== null) {
            $authDecision = $aiResult['auth_decision'] ?? 'passive_auth_allow';
            $riskScore    = $aiResult['risk_score']    ?? 0.0;
            $reasons      = $aiResult['reasons']       ?? [];

            Log::info('[RiskEngine] AI decision for login.', [
                'user_id'      => $user->id,
                'risk_score'   => $riskScore,
                'auth_decision' => $authDecision,
                'reasons'      => $reasons,
            ]);

            return match ($authDecision) {
                'block_access'      => true,  // Block → treat as MFA-required (strongest check)
                'challenge_otp'     => true,  // Challenge → trigger MFA
                default             => false, // passive_auth_allow → let through
            };
        }

        // --- 4. Fallback: Legacy IP/Location check (AI service offline) ---
        Log::warning('[RiskEngine] AI microservice unavailable, falling back to legacy IP check.');
        return $this->legacyIpRiskCheck($user, $ip);
    }

    /**
     * Record a successful login and return a new or existing Device ID.
     */
    public function recordSuccessfulLogin(User $user, Request $request): string
    {
        $deviceId = $request->cookie(self::DEVICE_COOKIE_NAME) ?: Str::uuid()->toString();
        $ip       = $request->ip();
        $position = Location::get($ip);
        $locationName = $position
            ? ($position->cityName ?: 'Unknown') . ', ' . ($position->countryName ?: 'Unknown')
            : null;

        LoginHistory::create([
            'user_id'    => $user->id,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'device_id'  => $deviceId,
            'location'   => [
                'name' => $locationName,
                'raw'  => $position ? $position->toArray() : null,
            ],
        ]);

        return $deviceId;
    }

    /**
     * Legacy IP/Location risk check (fallback when AI is offline).
     * Returns true if MFA should be triggered.
     */
    private function legacyIpRiskCheck(User $user, string $ip): bool
    {
        // Use generic testing IP if local
        $position     = Location::get($ip);
        $locationName = $position
            ? ($position->cityName ?: 'Unknown') . ', ' . ($position->countryName ?: 'Unknown')
            : null;

        $hasLoginFromLocationOrIp = LoginHistory::where('user_id', $user->id)
            ->where(function ($query) use ($ip, $locationName) {
                $query->where('ip_address', $ip);
                if ($locationName) {
                    $query->orWhere('location->name', $locationName);
                }
            })->exists();

        return !$hasLoginFromLocationOrIp;
    }

    /**
     * Detect device type from User-Agent string.
     * Returns 'mobile' | 'tablet' | 'desktop'.
     */
    private function detectDeviceType(string $userAgent): string
    {
        $ua = strtolower($userAgent);

        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }

        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }

        return 'desktop';
    }
}

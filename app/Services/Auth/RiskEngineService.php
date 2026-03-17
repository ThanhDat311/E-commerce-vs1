<?php

namespace App\Services\Auth;

use App\Models\AuthLog;
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
        $ip = $request->ip();

        // --- 0a. Check Explicit IP Whitelist/Blacklist ---
        $riskEntry = \App\Models\RiskList::where('type', 'ip')
            ->where('value', $ip)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->first();

        if ($riskEntry) {
            if ($riskEntry->action === 'whitelist') {
                Log::info('[RiskEngine] IP is explicitly whitelisted.', ['ip' => $ip]);

                return false;
            }

            if ($riskEntry->action === 'block' || $riskEntry->action === 'blacklist') {
                Log::warning('[RiskEngine] Explicit IP block triggered.', ['ip' => $ip, 'user_id' => $user->id]);

                AuthLog::create([
                    'user_id' => $user->id,
                    'session_id' => $request->hasSession() ? $request->session()->getId() : 'no-session',
                    'ip_address' => $ip,
                    'user_agent' => $request->userAgent(),
                    'risk_score' => 100,
                    'risk_level' => 'critical',
                    'auth_decision' => 'block_access',
                    'is_successful' => false,
                    'reasons' => ['IP explicitly blocked by admin'],
                ]);

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => __('Your IP address has been temporarily blocked for security reasons.'),
                ]);
            }
        }

        // --- 0b. Check Explicit User Whitelist ---
        $isUserWhitelisted = \App\Models\RiskList::where('type', 'user_id')
            ->where('value', (string) $user->id)
            ->where('action', 'whitelist')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->exists();

        if ($isUserWhitelisted) {
            Log::info('[RiskEngine] User is explicitly whitelisted.', ['user_id' => $user->id]);

            return false;
        }

        // --- 1. Check Device Trust ---
        $deviceId = $request->cookie(self::DEVICE_COOKIE_NAME);
        $isDeviceTrusted = false;

        if ($deviceId) {
            $isDeviceTrusted = LoginHistory::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->exists();
        }

        // We no longer return early here, so that the AI microservice is always called
        // and a Login Risk record is always created in AuthLog.

        // --- 2. Detect device type from User-Agent ---
        $userAgent = $request->userAgent() ?? '';
        $deviceType = $this->detectDeviceType($userAgent);

        // --- 3. Try AI Microservice ---
        $aiResult = $this->aiClient->predictLoginRisk(
            userId: $user->id,
            ip: $ip,
            userAgent: $userAgent,
            deviceType: $deviceType,
            country: null, // geolocation lookup done below if needed
        );

        if ($aiResult !== null) {
            $riskScore = (float) ($aiResult['risk_score'] ?? 0.0);
            $reasons = $aiResult['reasons'] ?? [];

            // Override risk_level and auth_decision based on risk_score
            // to prevent inconsistencies from AI microservice
            $riskLevel = $this->calculateRiskLevel($riskScore);
            $authDecision = $this->calculateDecision($riskScore);

            // Trusted device overrides the enforcement
            if ($isDeviceTrusted) {
                $authDecision = 'passive_auth_allow';
                $reasons[] = 'Allowed due to trusted device (AI logic bypassed for enforcement)';
            }

            Log::info('[RiskEngine] AI decision for login.', [
                'user_id' => $user->id,
                'risk_score' => $riskScore,
                'risk_level' => $riskLevel,
                'auth_decision' => $authDecision,
                'reasons' => $reasons,
            ]);

            // Resolve geo-location safely (may fail in test or local environments)
            $geoLocation = null;
            try {
                $position = Location::get($ip);
                $geoLocation = $position?->toArray();
            } catch (\Throwable $e) {
                Log::debug('[RiskEngine] Geo-location lookup failed.', ['ip' => $ip, 'error' => $e->getMessage()]);
            }

            AuthLog::create([
                'user_id' => $user->id,
                'session_id' => $request->hasSession() ? $request->session()->getId() : 'no-session',
                'ip_address' => $ip,
                'device_fingerprint' => $deviceId ?? $request->cookie(self::DEVICE_COOKIE_NAME) ?? null,
                'user_agent' => $userAgent,
                'geo_location' => $geoLocation,
                'risk_score' => $riskScore,
                'risk_level' => $riskLevel,
                'auth_decision' => $authDecision,
                'is_successful' => $authDecision === 'passive_auth_allow',
            ]);

            if ($authDecision === 'block_access') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'Your access has been blocked due to high security risk.',
                ]);
            }

            return match ($authDecision) {
                'challenge_otp' => true,
                'challenge_biometric' => true,
                default => false,
            };
        }

        // --- 4. Fallback: Legacy IP/Location check (AI service offline) ---
        Log::warning('[RiskEngine] AI microservice unavailable, falling back to legacy IP check.');

        if ($isDeviceTrusted) {
            return false;
        }

        return $this->legacyIpRiskCheck($user, $ip);
    }

    /**
     * Record a successful login and return a new or existing Device ID.
     */
    public function recordSuccessfulLogin(User $user, Request $request): string
    {
        $deviceId = $request->cookie(self::DEVICE_COOKIE_NAME) ?: Str::uuid()->toString();
        $ip = $request->ip();
        $position = Location::get($ip);
        $locationName = $position
            ? ($position->cityName ?: 'Unknown').', '.($position->countryName ?: 'Unknown')
            : null;

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

    /**
     * Legacy IP/Location risk check (fallback when AI is offline).
     * Returns true if MFA should be triggered.
     */
    private function legacyIpRiskCheck(User $user, string $ip): bool
    {
        // Use generic testing IP if local
        $position = Location::get($ip);
        $locationName = $position
            ? ($position->cityName ?: 'Unknown').', '.($position->countryName ?: 'Unknown')
            : null;

        $hasLoginFromLocationOrIp = LoginHistory::where('user_id', $user->id)
            ->where(function ($query) use ($ip, $locationName) {
                $query->where('ip_address', $ip);
                if ($locationName) {
                    $query->orWhere('location->name', $locationName);
                }
            })->exists();

        return ! $hasLoginFromLocationOrIp;
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

    /**
     * Calculate risk level from risk score.
     *
     * Thresholds:
     *   0.00 – 0.29 → low
     *   0.30 – 0.59 → medium
     *   0.60 – 0.79 → high
     *   0.80 – 1.00 → critical
     */
    private function calculateRiskLevel(float $score): string
    {
        return match (true) {
            $score >= 0.80 => 'critical',
            $score >= 0.60 => 'high',
            $score >= 0.30 => 'medium',
            default => 'low',
        };
    }

    /**
     * Calculate auth decision from risk score.
     *
     * Thresholds:
     *   0.00 – 0.29 → passive_auth_allow (safe)
     *   0.30 – 0.59 → challenge_otp
     *   0.60 – 0.79 → challenge_biometric
     *   0.80 – 1.00 → block_access
     */
    private function calculateDecision(float $score): string
    {
        return match (true) {
            $score >= 0.80 => 'block_access',
            $score >= 0.60 => 'challenge_biometric',
            $score >= 0.30 => 'challenge_otp',
            default => 'passive_auth_allow',
        };
    }
}

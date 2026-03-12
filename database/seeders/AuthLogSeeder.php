<?php

namespace Database\Seeders;

use App\Models\AuthLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuthLogSeeder extends Seeder
{
    /**
     * Seed consistent AuthLog data for Login Risk Logs.
     *
     * Thresholds match RiskEngineService:
     *   0.00 – 0.29 → low / passive_auth_allow / is_successful=true
     *   0.30 – 0.59 → medium / challenge_otp / is_successful=false
     *   0.60 – 0.79 → high / challenge_biometric / is_successful=false
     *   0.80 – 1.00 → critical / block_access / is_successful=false
     */
    public function run(): void
    {
        $users = User::limit(5)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping AuthLogSeeder.');

            return;
        }

        $scenarios = [
            // Low risk — allowed
            ['risk_score' => 0.05, 'risk_level' => 'low', 'auth_decision' => 'passive_auth_allow', 'is_successful' => true],
            ['risk_score' => 0.12, 'risk_level' => 'low', 'auth_decision' => 'passive_auth_allow', 'is_successful' => true],
            ['risk_score' => 0.18, 'risk_level' => 'low', 'auth_decision' => 'passive_auth_allow', 'is_successful' => true],
            ['risk_score' => 0.22, 'risk_level' => 'low', 'auth_decision' => 'passive_auth_allow', 'is_successful' => true],
            ['risk_score' => 0.28, 'risk_level' => 'low', 'auth_decision' => 'passive_auth_allow', 'is_successful' => true],

            // Medium risk — challenge OTP
            ['risk_score' => 0.32, 'risk_level' => 'medium', 'auth_decision' => 'challenge_otp', 'is_successful' => false],
            ['risk_score' => 0.41, 'risk_level' => 'medium', 'auth_decision' => 'challenge_otp', 'is_successful' => false],
            ['risk_score' => 0.48, 'risk_level' => 'medium', 'auth_decision' => 'challenge_otp', 'is_successful' => false],
            ['risk_score' => 0.55, 'risk_level' => 'medium', 'auth_decision' => 'challenge_otp', 'is_successful' => false],

            // High risk — challenge biometric
            ['risk_score' => 0.62, 'risk_level' => 'high', 'auth_decision' => 'challenge_biometric', 'is_successful' => false],
            ['risk_score' => 0.68, 'risk_level' => 'high', 'auth_decision' => 'challenge_biometric', 'is_successful' => false],
            ['risk_score' => 0.74, 'risk_level' => 'high', 'auth_decision' => 'challenge_biometric', 'is_successful' => false],

            // Critical risk — blocked
            ['risk_score' => 0.82, 'risk_level' => 'critical', 'auth_decision' => 'block_access', 'is_successful' => false],
            ['risk_score' => 0.91, 'risk_level' => 'critical', 'auth_decision' => 'block_access', 'is_successful' => false],
            ['risk_score' => 0.97, 'risk_level' => 'critical', 'auth_decision' => 'block_access', 'is_successful' => false],
        ];

        $ips = [
            '192.168.1.100', '192.168.1.105', '10.0.0.15',
            '172.16.0.42', '203.113.152.10', '113.160.92.88',
            '42.118.234.50', '14.225.0.120', '115.73.210.5',
            '58.186.75.200', '27.72.100.88', '1.55.210.30',
            '103.7.36.19', '118.70.125.44', '45.124.95.71',
        ];

        $locations = [
            ['city' => 'Hanoi', 'country' => 'VN'],
            ['city' => 'Ho Chi Minh', 'country' => 'VN'],
            ['city' => 'Da Nang', 'country' => 'VN'],
            ['city' => 'Singapore', 'country' => 'SG'],
            ['city' => 'Bangkok', 'country' => 'TH'],
        ];

        // Clear existing mock data (disable FK checks for truncate)
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        AuthLog::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($scenarios as $index => $scenario) {
            $user = $users[$index % $users->count()];

            AuthLog::create([
                'user_id' => $user->id,
                'session_id' => Str::random(20),
                'ip_address' => $ips[$index % count($ips)],
                'device_fingerprint' => Str::uuid()->toString(),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'geo_location' => $locations[$index % count($locations)],
                'risk_score' => $scenario['risk_score'],
                'risk_level' => $scenario['risk_level'],
                'auth_decision' => $scenario['auth_decision'],
                'is_successful' => $scenario['is_successful'],
                'created_at' => now()->subMinutes(rand(5, 4320)),
            ]);
        }

        $this->command->info('Seeded '.count($scenarios).' consistent AuthLog records.');
    }
}

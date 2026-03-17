<?php

namespace Database\Seeders;

use App\Models\RiskRule;
use Illuminate\Database\Seeder;

class LoginRiskRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
                'rule_key' => 'failed_attempts_3',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 25,
                'risk_level' => 'high',
                'description' => '3 or more failed login attempts within 15 minutes from the same IP — indicates brute-force or password spray activity',
                'is_active' => true,
            ],
            [
                'rule_key' => 'failed_attempts_5',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 45,
                'risk_level' => 'critical',
                'description' => '5 or more failed login attempts within 30 minutes — high-confidence brute-force attack pattern',
                'is_active' => true,
            ],
            [
                'rule_key' => 'new_ip_address',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 20,
                'risk_level' => 'medium',
                'description' => 'Login attempt from an IP address that has never been associated with a successful login for this account',
                'is_active' => true,
            ],
            [
                'rule_key' => 'suspicious_login_hour',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 15,
                'risk_level' => 'medium',
                'description' => 'Login during off-hours (12:00 AM – 4:00 AM local time) when legitimate user activity is typically low',
                'is_active' => true,
            ],
            [
                'rule_key' => 'new_device_fingerprint',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 20,
                'risk_level' => 'medium',
                'description' => 'Login from an unrecognized device fingerprint — device has not been previously trusted by the user',
                'is_active' => true,
            ],
            [
                'rule_key' => 'vpn_proxy_detected',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 35,
                'risk_level' => 'high',
                'description' => 'Login originating from a known VPN exit node, proxy server, or anonymization service IP range',
                'is_active' => true,
            ],
            [
                'rule_key' => 'impossible_travel',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 40,
                'risk_level' => 'critical',
                'description' => 'Consecutive logins from geographically distant locations within an impossibly short time window (rapid location change)',
                'is_active' => true,
            ],
            [
                'rule_key' => 'credential_stuffing_pattern',
                'ai_type' => RiskRule::TYPE_LOGIN,
                'weight' => 50,
                'risk_level' => 'critical',
                'description' => 'Login pattern matches known credential stuffing behavior — high volume of attempts across multiple accounts from same IP subnet',
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            RiskRule::updateOrCreate(
                ['rule_key' => $rule['rule_key']],
                [
                    'ai_type' => $rule['ai_type'],
                    'weight' => $rule['weight'],
                    'risk_level' => $rule['risk_level'],
                    'description' => $rule['description'],
                    'is_active' => $rule['is_active'],
                ]
            );
        }

        echo '✅ RiskRule (Login) seeded with '.count($rules)." rules\n";
    }
}

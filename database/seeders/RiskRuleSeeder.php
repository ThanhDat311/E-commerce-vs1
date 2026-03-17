<?php

namespace Database\Seeders;

use App\Models\RiskRule;
use Illuminate\Database\Seeder;

class RiskRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
                'rule_key' => 'guest_checkout',
                'ai_type' => RiskRule::TYPE_TRANSACTION,
                'weight' => 20,
                'risk_level' => 'medium',
                'description' => 'Guest checkout increases fraud risk as there is no user history to validate against',
                'is_active' => true,
            ],
            [
                'rule_key' => 'new_user_24h',
                'ai_type' => RiskRule::TYPE_TRANSACTION,
                'weight' => 15,
                'risk_level' => 'medium',
                'description' => 'New user account (created less than 24 hours ago) has higher fraud risk',
                'is_active' => true,
            ],
            [
                'rule_key' => 'high_value_5000',
                'ai_type' => RiskRule::TYPE_TRANSACTION,
                'weight' => 25,
                'risk_level' => 'high',
                'description' => 'Orders with total amount greater than $5000 require additional scrutiny',
                'is_active' => true,
            ],
            [
                'rule_key' => 'high_value_1000',
                'ai_type' => RiskRule::TYPE_TRANSACTION,
                'weight' => 10,
                'risk_level' => 'medium',
                'description' => 'Orders with total amount between $1000-$5000 indicate medium risk',
                'is_active' => true,
            ],
            [
                'rule_key' => 'suspicious_time',
                'ai_type' => RiskRule::TYPE_TRANSACTION,
                'weight' => 30,
                'risk_level' => 'high',
                'description' => 'Orders placed during suspicious hours (12:00 AM - 4:00 AM) have higher fraud risk',
                'is_active' => true,
            ],
            [
                'rule_key' => 'large_quantity',
                'ai_type' => RiskRule::TYPE_TRANSACTION,
                'weight' => 20,
                'risk_level' => 'medium',
                'description' => 'Bulk orders with quantity greater than 10 items may indicate suspicious activity',
                'is_active' => true,
            ],
            [
                'rule_key' => 'round_amount',
                'ai_type' => RiskRule::TYPE_TRANSACTION,
                'weight' => 10,
                'risk_level' => 'low',
                'description' => 'Orders with round amounts (multiples of 100) may indicate testing or suspicious patterns',
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

        echo '✅ RiskRule (Transaction) seeded with '.count($rules)." rules\n";
    }
}

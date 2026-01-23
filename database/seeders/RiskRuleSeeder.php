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
        // Define initial risk rules from AIDecisionEngine constants
        $rules = [
            [
                'rule_key' => 'guest_checkout',
                'weight' => 20,
                'description' => 'Guest checkout increases fraud risk as there is no user history to validate against',
                'is_active' => true,
            ],
            [
                'rule_key' => 'new_user_24h',
                'weight' => 15,
                'description' => 'New user account (created less than 24 hours ago) has higher fraud risk',
                'is_active' => true,
            ],
            [
                'rule_key' => 'high_value_5000',
                'weight' => 25,
                'description' => 'Orders with total amount greater than $5000 require additional scrutiny',
                'is_active' => true,
            ],
            [
                'rule_key' => 'high_value_1000',
                'weight' => 10,
                'description' => 'Orders with total amount between $1000-$5000 indicate medium risk',
                'is_active' => true,
            ],
            [
                'rule_key' => 'suspicious_time',
                'weight' => 30,
                'description' => 'Orders placed during suspicious hours (12:00 AM - 4:00 AM) have higher fraud risk',
                'is_active' => true,
            ],
            [
                'rule_key' => 'large_quantity',
                'weight' => 20,
                'description' => 'Bulk orders with quantity greater than 10 items may indicate suspicious activity',
                'is_active' => true,
            ],
            [
                'rule_key' => 'round_amount',
                'weight' => 10,
                'description' => 'Orders with round amounts (multiples of 100) may indicate testing or suspicious patterns',
                'is_active' => true,
            ],
        ];

        // Insert all rules, ignoring duplicates
        foreach ($rules as $rule) {
            RiskRule::updateOrCreate(
                ['rule_key' => $rule['rule_key']],
                [
                    'weight' => $rule['weight'],
                    'description' => $rule['description'],
                    'is_active' => $rule['is_active'],
                ]
            );
        }

        echo "✅ RiskRule seeded with " . count($rules) . " rules\n";
    }
}

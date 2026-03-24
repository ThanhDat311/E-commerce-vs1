<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiFeatureStore>
 */
class AiFeatureStoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'total_amount' => fake()->randomFloat(2, 10, 500),
            'ip_address' => fake()->ipv4(),
            'risk_score' => fake()->randomFloat(3, 0, 1),
            'reasons' => [fake()->word(), fake()->word()],
            'label' => fake()->boolean() ? 'fraud' : 'legit',
            'ai_insight' => fake()->sentence(),
        ];
    }
}

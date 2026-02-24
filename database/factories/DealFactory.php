<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'discount_type' => fake()->randomElement(['percent', 'fixed', 'bogo', 'flash']),
            'discount_value' => fake()->randomFloat(2, 5, 50),
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(7),
            'usage_limit' => null,
            'usage_count' => 0,
            'apply_scope' => 'product',
            'vendor_id' => null,
            'priority' => 0,
            'status' => 'active',
            'created_by' => \App\Models\User::factory(),
            'approved_by' => null,
        ];
    }
}

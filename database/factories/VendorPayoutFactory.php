<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VendorPayout;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorPayoutFactory extends Factory
{
    protected $model = VendorPayout::class;

    public function definition(): array
    {
        $commissionTotal = $this->faker->randomFloat(2, 10, 100);
        $amount = $this->faker->randomFloat(2, 100, 1000);

        return [
            'vendor_id' => User::factory(),
            'amount' => $amount,
            'commission_total' => $commissionTotal,
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed']),
            'reference' => null,
            'processed_by' => null,
            'processed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn() => [
            'status' => 'completed',
            'processed_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }
}

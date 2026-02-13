<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    protected $model = Commission::class;

    public function definition(): array
    {
        $orderTotal = $this->faker->randomFloat(2, 20, 500);
        $rate = $this->faker->randomFloat(2, 5, 12);
        $commissionAmount = round($orderTotal * $rate / 100, 2);

        return [
            'order_id' => Order::factory(),
            'vendor_id' => null,
            'order_total' => $orderTotal,
            'commission_rate' => $rate,
            'commission_amount' => $commissionAmount,
            'status' => $this->faker->randomElement(['pending', 'paid']),
            'paid_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn() => [
            'status' => 'paid',
            'paid_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn() => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }
}

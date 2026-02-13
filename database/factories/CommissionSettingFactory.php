<?php

namespace Database\Factories;

use App\Models\CommissionSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionSettingFactory extends Factory
{
    protected $model = CommissionSetting::class;

    public function definition(): array
    {
        return [
            'vendor_id' => null,
            'rate' => $this->faker->randomFloat(2, 3, 15),
            'is_active' => true,
        ];
    }

    public function forVendor(User $vendor): static
    {
        return $this->state(fn() => [
            'vendor_id' => $vendor->id,
        ]);
    }

    public function global(): static
    {
        return $this->state(fn() => [
            'vendor_id' => null,
            'rate' => 8.50,
        ]);
    }
}

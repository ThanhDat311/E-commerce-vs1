<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vendor_id' => \App\Models\User::factory()->create(['role_id' => 4])->id,
            'category_id' => \App\Models\Category::factory(),
            'name' => fake()->words(3, true),
            'sku' => 'SKU-'.fake()->unique()->numerify('######'),
            'price' => fake()->randomFloat(2, 10, 500),
            'sale_price' => null,
            'stock_quantity' => fake()->numberBetween(0, 100),
            'image_url' => null,
            'is_new' => fake()->boolean(20),
            'is_featured' => fake()->boolean(10),
            'description' => fake()->paragraph(),
        ];
    }
}

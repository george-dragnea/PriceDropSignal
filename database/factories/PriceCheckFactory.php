<?php

namespace Database\Factories;

use App\Models\ProductUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceCheck>
 */
class PriceCheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_url_id' => ProductUrl::factory(),
            'price_cents' => fake()->numberBetween(100, 100000),
            'checked_at' => now(),
        ];
    }
}

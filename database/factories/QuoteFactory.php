<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),
            'number' => 'TK-' . now()->year . '-' . $this->faker->unique()->numberBetween(100, 999),
            'status' => $this->faker->randomElement(['draft', 'sent', 'accepted', 'expired']),
            'currency' => 'TRY',
            'discount_total' => $this->faker->randomFloat(2, 0, 100),
            'subtotal' => 0,
            'tax_total' => 0,
            'grand_total' => 0,
            'valid_until' => now()->addDays(30),
            'sent_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', 'now'),
            'accepted_at' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
            'notes' => $this->faker->sentence(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $cycle = $this->faker->randomElement(['monthly', 'quarterly', 'yearly', 'biennial']);

        // Calculate end_date based on cycle
        $endDate = match ($cycle) {
            'monthly' => (clone $startDate)->modify('+1 month'),
            'quarterly' => (clone $startDate)->modify('+3 months'),
            'yearly' => (clone $startDate)->modify('+1 year'),
            'biennial' => (clone $startDate)->modify('+2 years'),
            default => (clone $startDate)->modify('+1 year'),
        };

        $type = $this->faker->randomElement(['hosting', 'domain', 'ssl', 'email', 'other']);

        return [
            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),
            'provider_id' => Provider::inRandomOrder()->first()->id ?? Provider::factory(),
            'type' => $type,
            'name' => $this->faker->words(3, true),
            'identifier_code' => strtoupper($this->faker->bothify('???-###')),
            'cycle' => $cycle,
            'payment_type' => $this->faker->randomElement(['installment', 'upfront']),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'suspended', 'expired']), // 60% active
            'currency' => 'TRY',
            'price' => $this->faker->randomFloat(2, 50, 5000),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}

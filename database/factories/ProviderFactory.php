<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $allTypes = ['hosting', 'domain', 'ssl', 'email', 'other'];
        $selectedTypes = $this->faker->randomElements($allTypes, $this->faker->numberBetween(1, 3));

        return [
            'name' => $this->faker->company(),
            'types' => $selectedTypes,
            'website' => $this->faker->optional(0.8)->domainName(),
            'email' => $this->faker->optional(0.7)->companyEmail(),
            'phone' => $this->faker->numerify('0### ### ## ##'),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}

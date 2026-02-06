<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['individual', 'corporate']);

        $turkishCities = ['İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Adana', 'Konya', 'Gaziantep', 'Mersin', 'Kayseri'];
        $turkishDistricts = ['Kadıköy', 'Beşiktaş', 'Şişli', 'Çankaya', 'Keçiören', 'Karşıyaka', 'Bornova', 'Nilüfer', 'Osmangazi'];

        return [
            'type' => $type,
            'name' => $type === 'individual'
                ? $this->faker->name()
                : $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('0### ### ## ##'),
            'mobile_phone' => $this->faker->numerify('05## ### ## ##'),
            'website' => $type === 'corporate' ? $this->faker->domainName() : null,
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->randomElement($turkishCities),
            'district' => $this->faker->randomElement($turkishDistricts),
            'postal_code' => $this->faker->numerify('#####'),
            'country' => 'TR',
            'tax_or_identity_number' => $type === 'corporate'
                ? $this->faker->numerify('##########')
                : $this->faker->numerify('###########'),
            'invoice_address' => null, // Çoğunlukla aynı adres
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'inactive']), // 75% active
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Individual customer state
     */
    public function individual(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'individual',
            'name' => $this->faker->name(),
            'website' => null,
            'tax_or_identity_number' => $this->faker->numerify('###########'),
        ]);
    }

    /**
     * Corporate customer state
     */
    public function corporate(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'corporate',
            'name' => $this->faker->company(),
            'website' => $this->faker->domainName(),
            'tax_or_identity_number' => $this->faker->numerify('##########'),
        ]);
    }

    /**
     * Active customer state
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }
}

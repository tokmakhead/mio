<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issueDate = $this->faker->dateTimeBetween('-2 months', 'now');
        $dueDate = (clone $issueDate)->modify('+30 days');

        return [
            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),
            'number' => 'FAT-' . now()->year . '-' . $this->faker->unique()->numberBetween(100, 999),
            'status' => $this->faker->randomElement(['draft', 'sent', 'paid', 'overdue']),
            'currency' => 'TRY',
            'discount_total' => 0,
            'subtotal' => 0,
            'tax_total' => 0,
            'grand_total' => 0,
            'paid_amount' => 0,
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'notes' => $this->faker->sentence(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first customer and provider for test scenario
        $customer = Customer::first();
        $provider = Provider::first();

        if (!$customer || !$provider) {
            $this->command->warn('No customers or providers found. Please seed them first.');
            return;
        }

        // Test Scenario Services for MRR = 270₺

        // 2 Hosting (yearly, 1200₺) → MRR: 100₺ each = 200₺
        Service::create([
            'customer_id' => $customer->id,
            'provider_id' => $provider->id,
            'type' => 'hosting',
            'name' => 'Web Hosting Pro',
            'identifier_code' => 'HST-001',
            'cycle' => 'yearly',
            'payment_type' => 'upfront',
            'status' => 'active',
            'currency' => 'TRY',
            'price' => 1200.00,
            'start_date' => now()->subMonths(6),
            'end_date' => now()->addMonths(6),
        ]);

        Service::create([
            'customer_id' => $customer->id,
            'provider_id' => $provider->id,
            'type' => 'hosting',
            'name' => 'Email Hosting',
            'identifier_code' => 'HST-002',
            'cycle' => 'yearly',
            'payment_type' => 'installment',
            'status' => 'active',
            'currency' => 'TRY',
            'price' => 1200.00,
            'start_date' => now()->subMonths(3),
            'end_date' => now()->addMonths(9),
        ]);

        // 1 Domain (monthly, 50₺) → MRR: 50₺
        Service::create([
            'customer_id' => $customer->id,
            'provider_id' => $provider->id,
            'type' => 'domain',
            'name' => 'example.com',
            'identifier_code' => 'DOM-001',
            'cycle' => 'monthly',
            'payment_type' => 'installment',
            'status' => 'active',
            'currency' => 'TRY',
            'price' => 50.00,
            'start_date' => now()->subDays(15),
            'end_date' => now()->addDays(15),
        ]);

        // 1 SSL (biennial, 480₺) → MRR: 20₺
        Service::create([
            'customer_id' => $customer->id,
            'provider_id' => $provider->id,
            'type' => 'ssl',
            'name' => 'Wildcard SSL Certificate',
            'identifier_code' => 'SSL-001',
            'cycle' => 'biennial',
            'payment_type' => 'upfront',
            'status' => 'active',
            'currency' => 'TRY',
            'price' => 480.00,
            'start_date' => now()->subYear(),
            'end_date' => now()->addYear(),
        ]);

        // Additional random services (11 more to make 15 total)
        Service::factory()->count(11)->create();

        $this->command->info('✓ 15 services seeded (4 test + 11 random)');
        $this->command->info('✓ Expected MRR: 270₺ (100+100+50+20)');
    }
}

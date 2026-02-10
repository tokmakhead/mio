<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 5 Realist Customers
        $customersData = [
            ['type' => 'corporate', 'name' => 'TechSolutions Ltd. Şti.', 'email' => 'info@techsolutions.com.tr', 'phone' => '0212 555 1020', 'city' => 'İstanbul', 'status' => 'active', 'tax_or_identity_number' => '8430012945'],
            ['type' => 'corporate', 'name' => 'Lojistik Arası Nakliyat', 'email' => 'destek@lojiarasi.com', 'phone' => '0216 444 0 444', 'city' => 'İstanbul', 'status' => 'active', 'tax_or_identity_number' => '1239874560'],
            ['type' => 'individual', 'name' => 'Ahmet Yılmaz', 'email' => 'ahmet@yilmaz.me', 'phone' => '0532 111 22 33', 'city' => 'Ankara', 'status' => 'active', 'tax_or_identity_number' => '11111111111'],
            ['type' => 'individual', 'name' => 'Zeynep Kaya', 'email' => 'zeynep@artdesign.com', 'phone' => '0544 555 66 77', 'city' => 'İzmir', 'status' => 'active', 'tax_or_identity_number' => '22222222222'],
            ['type' => 'corporate', 'name' => 'Mavi Bulut E-Ticaret', 'email' => 'ceo@mavibulut.com', 'phone' => '0232 999 88 77', 'city' => 'İzmir', 'status' => 'active', 'tax_or_identity_number' => '4561237890'],
        ];

        foreach ($customersData as $data) {
            Customer::updateOrCreate(['email' => $data['email']], $data);
        }

        // 10 Professional Providers
        $providersData = [
            ['name' => 'Global Cloud Services', 'types' => ['hosting', 'other'], 'website' => 'globalcloud.com'],
            ['name' => 'NetSpeed Infrastructure', 'types' => ['hosting'], 'website' => 'netspeed.tech'],
            ['name' => 'SecureTrust SSL', 'types' => ['ssl'], 'website' => 'securetrust.io'],
            ['name' => 'DomainMaster TR', 'types' => ['domain'], 'website' => 'domainmaster.com.tr'],
            ['name' => 'FastMail Pro', 'types' => ['email'], 'website' => 'fastmailpro.net'],
            ['name' => 'Ocean Hosting', 'types' => ['hosting'], 'website' => 'oceanhosting.com'],
            ['name' => 'CryptoCert', 'types' => ['ssl'], 'website' => 'cryptocert.com'],
            ['name' => 'EuroDomain Store', 'types' => ['domain'], 'website' => 'eurodomain.eu'],
            ['name' => 'MailFlow Systems', 'types' => ['email'], 'website' => 'mailflow.co'],
            ['name' => 'Hybrid Data Centers', 'types' => ['hosting', 'other'], 'website' => 'hybrid-dc.com'],
        ];

        foreach ($providersData as $data) {
            Provider::updateOrCreate(['name' => $data['name']], $data);
        }

        // 15 Dynamic Services
        $allCustomers = Customer::all();
        $allProviders = Provider::all();

        $serviceTitles = [
            'hosting' => ['CPanel Web Hosting', 'Litespeed Cloud Hosting', 'WordPress Managed Hosting'],
            'domain' => ['Corporate Domain Registration', 'Domain Privacy Protection', 'Premium .com.tr Reservation'],
            'ssl' => ['EV SSL Certificate', 'Wildcard SSL Protection', 'Standard DV SSL'],
            'email' => ['Enterprise Email Hosting', 'Spam Protection Filter', 'Business Mail Box'],
            'other' => ['Managed IT Support', 'Cloud Backup Service', 'VPC Server Instance'],
        ];

        $currencies = ['TRY', 'USD', 'EUR'];
        $cycles = ['monthly', 'yearly', 'biennial'];

        for ($i = 0; $i < 15; $i++) {
            $type = collect(['hosting', 'domain', 'ssl', 'email', 'other'])->random();
            $eligibleProviders = $allProviders->filter(function ($p) use ($type) {
                return is_array($p->types) && in_array($type, $p->types);
            });

            $provider = $eligibleProviders->isNotEmpty() ? $eligibleProviders->random() : $allProviders->random();
            $customer = $allCustomers->random();

            Service::create([
                'customer_id' => $customer->id,
                'provider_id' => $provider->id,
                'type' => $type,
                'name' => collect($serviceTitles[$type])->random(),
                'identifier_code' => 'SRV-' . strtoupper(Str::random(8)),
                'cycle' => collect($cycles)->random(),
                'payment_type' => 'upfront',
                'status' => 'active',
                'currency' => collect($currencies)->random(),
                'price' => rand(100, 5000) / 10,
                'start_date' => now()->subMonths(rand(1, 12)),
                'end_date' => now()->addMonths(rand(1, 24)),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\License;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\LedgerEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProductionDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset all data (ORDER MATTERS due to foreign keys)
        $this->command->info('Resetting database...');

        // Disable foreign key checks for truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Payment::truncate();
        LedgerEntry::truncate();
        InvoiceItem::truncate();
        Invoice::truncate();
        QuoteItem::truncate();
        Quote::truncate();
        Service::truncate();
        Provider::truncate();
        Customer::truncate();
        License::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Users & License
        $this->command->info('Creating license...');
        License::create([
            'license_key' => 'MIONEX-DEMO-2026-PRO',
            'client_name' => 'Demo Client',
            'domain' => 'mionex.test',
            'status' => 'active',
            'expires_at' => Carbon::now()->addYears(10),
            'last_check_at' => Carbon::now(),
        ]);

        $this->command->info('Creating users...');
        User::create([
            'name' => 'Mionex Admin',
            'email' => 'admin@mionex.test',
            'password' => Hash::make('mionex123'),
            'role' => 'admin',
            'demo_readonly' => false,
        ]);

        User::create([
            'name' => 'Demo Kullanıcı',
            'email' => 'demo@mionex.test',
            'password' => Hash::make('demo123'),
            'role' => 'user',
            'demo_readonly' => true,
        ]);

        // 3. Create Turkish Providers (10)
        $this->command->info('Creating 10 Turkish Providers...');
        $providerNames = [
            'Türk Telekom A.Ş.',
            'Turkcell İletişim',
            'Vodafone Türkiye',
            'Amazon Web Services (AWS)',
            'Google Cloud Turkey',
            'DigitalOcean Türkiye',
            'Natro Hosting',
            'İsim Tescil',
            'Turhost',
            'Grid Group Yazılım'
        ];

        $providers = [];
        foreach ($providerNames as $name) {
            $providers[] = Provider::create([
                'name' => $name,
                'types' => ['hosting', 'domain', 'ssl'],
                'email' => strtolower(str_replace([' ', '.'], '', $name)) . '@provider.com',
                'phone' => '0212' . rand(1000000, 9999999),
                'website' => 'https://www.' . strtolower(str_replace([' ', '.'], '', $name)) . '.com.tr',
                'notes' => 'Türkiye genelinde hizmet veriyor.',
            ]);
        }

        // 4. Create Turkish Customers (20)
        $this->command->info('Creating Turkish Customers...');
        $individualNames = [
            'Ahmet Yılmaz',
            'Mehmet Demir',
            'Ayşe Kaya',
            'Fatma Şahin',
            'Mustafa Çelik',
            'Zeynep Öztürk',
            'Emre Aydın',
            'Burak Yıldız',
            'Ömer Arslan',
            'Canan Doğan'
        ];
        $corporateNames = [
            'Atlas Yazılım ve Danışmanlık',
            'Piramit Mimarlık Ltd.',
            'Tekno Market A.Ş.',
            'Global Lojistik Çözümleri',
            'Ege İnşaat Grubu',
            'Marmara Gıda Pazarlama',
            'Trend Moda Tekstil',
            'Vizyon Eğitim Kurumları',
            'Mavi Enerji Sanayi',
            'Doruk Sağlık Hizmetleri'
        ];

        $customers = [];
        foreach ($individualNames as $name) {
            $customers[] = Customer::create([
                'type' => 'individual',
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'phone' => '0212' . rand(1000000, 9999999),
                'mobile_phone' => '05' . rand(30, 55) . rand(1000000, 9999999),
                'status' => 'active',
                'city' => 'İstanbul',
                'district' => 'Kadıköy',
                'tax_or_identity_number' => rand(100000000, 999999999),
            ]);
        }
        foreach ($corporateNames as $name) {
            $customers[] = Customer::create([
                'type' => 'corporate',
                'name' => $name,
                'email' => 'info@' . strtolower(explode(' ', $name)[0]) . '.com.tr',
                'phone' => '0212' . rand(1000000, 9999999),
                'status' => 'active',
                'city' => 'İstanbul',
                'district' => 'Levent',
                'tax_or_identity_number' => rand(1000000000, 9999999999),
            ]);
        }

        // 5. Create Turkish Services (15)
        $this->command->info('Creating 15 Turkish Services...');
        $serviceTemplates = [
            ['name' => 'Linux Reseller Hosting', 'type' => 'hosting', 'price' => 4500, 'cycle' => 'yearly'],
            ['name' => 'Kurumsal E-Posta Servisi', 'type' => 'email', 'price' => 1200, 'cycle' => 'yearly'],
            ['name' => 'Cloud VPS - 4vCPU / 8GB', 'type' => 'hosting', 'price' => 850, 'cycle' => 'monthly'],
            ['name' => 'WordPress Optimize Hosting', 'type' => 'hosting', 'price' => 150, 'cycle' => 'monthly'],
            ['name' => 'Web Güvenlik Paketi', 'type' => 'other', 'price' => 550, 'cycle' => 'monthly'],
            ['name' => 'Sistem Bakım Anlaşması', 'type' => 'other', 'price' => 2500, 'cycle' => 'monthly'],
            ['name' => '.com Domain Tescili', 'type' => 'domain', 'price' => 350, 'cycle' => 'yearly'],
            ['name' => '.com.tr Domain Tescili', 'type' => 'domain', 'price' => 180, 'cycle' => 'yearly'],
            ['name' => 'Standart SSL Sertifikası', 'type' => 'ssl', 'price' => 450, 'cycle' => 'yearly'],
            ['name' => 'Wildcard Business SSL', 'type' => 'ssl', 'price' => 2400, 'cycle' => 'yearly'],
            ['name' => 'Yedekleme Servisi - 100GB', 'type' => 'other', 'price' => 300, 'cycle' => 'monthly'],
            ['name' => 'Yönetilen Sunucu Hizmeti', 'type' => 'other', 'price' => 2500, 'cycle' => 'monthly'],
            ['name' => 'Güvenlik Duvarı (WAF)', 'type' => 'other', 'price' => 600, 'cycle' => 'monthly'],
            ['name' => 'Özel IP Adresi', 'type' => 'other', 'price' => 85, 'cycle' => 'monthly'],
            ['name' => 'Teknik Destek Paketi', 'type' => 'other', 'price' => 380, 'cycle' => 'monthly'],
        ];

        $services = [];
        foreach ($serviceTemplates as $index => $tpl) {
            $services[] = Service::create([
                'customer_id' => $customers[array_rand($customers)]->id,
                'provider_id' => $providers[array_rand($providers)]->id,
                'type' => $tpl['type'],
                'name' => $tpl['name'],
                'identifier_code' => strtoupper($tpl['type']) . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'cycle' => $tpl['cycle'],
                'payment_type' => 'upfront',
                'status' => 'active',
                'currency' => 'TRY',
                'price' => $tpl['price'],
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => $tpl['cycle'] == 'monthly' ? Carbon::now()->addMonth() : Carbon::now()->addYear(),
            ]);
        }

        // 6. Generate Financial Data (Invoices & Payments for last 6 months)
        $this->command->info('Generating 6 months of financial data...');
        for ($i = 6; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $this->command->comment('Seeding month: ' . $monthDate->format('F Y'));

            // 5 Invoices per month
            for ($j = 0; $j < 5; $j++) {
                $customer = $customers[array_rand($customers)];
                $status = rand(0, 10) > 2 ? 'paid' : 'sent'; // Most are paid

                $issueDate = $monthDate->copy()->addDays(rand(1, 28));
                $dueDate = $issueDate->copy()->addDays(rand(7, 21));

                $invoice = Invoice::create([
                    'customer_id' => $customer->id,
                    'number' => 'INV-' . $monthDate->format('Ym') . str_pad($j + 1, 3, '0', STR_PAD_LEFT),
                    'issue_date' => $issueDate,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'subtotal' => 0,
                    'tax_total' => 0,
                    'grand_total' => 0,
                    'paid_amount' => 0,
                    'currency' => 'TRY',
                    'notes' => 'Demo verisidir.',
                ]);

                // 1-3 items per invoice
                $subtotal = 0;
                $itemCount = rand(1, 3);
                for ($k = 0; $k < $itemCount; $k++) {
                    $service = $services[array_rand($services)];
                    $qty = rand(1, 4);
                    $price = $service->price;
                    $tax = $price * $qty * 0.20;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'service_id' => $service->id,
                        'description' => $service->name,
                        'qty' => $qty,
                        'unit_price' => $price,
                        'vat_rate' => 20,
                        'line_subtotal' => $price * $qty,
                        'line_tax' => $tax,
                        'line_total' => ($price * $qty) + $tax,
                    ]);
                    $subtotal += ($price * $qty);
                }

                $taxTotal = $subtotal * 0.20;
                $invoice->update([
                    'subtotal' => $subtotal,
                    'tax_total' => $taxTotal,
                    'grand_total' => $subtotal + $taxTotal,
                ]);

                if ($status === 'paid') {
                    $paidAmount = $invoice->grand_total;
                    $paidAt = $issueDate->copy()->addDays(rand(0, 5));
                    $invoice->update([
                        'paid_amount' => $paidAmount,
                        'paid_at' => $paidAt,
                    ]);

                    // Create payment entry
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'customer_id' => $customer->id,
                        'amount' => $paidAmount,
                        'paid_at' => $paidAt,
                        'method' => ['bank_transfer', 'credit_card', 'cash'][rand(0, 2)],
                        'currency' => 'TRY',
                        'note' => 'Demo ödeme kaydı.',
                    ]);

                    // Add Ledger Entry
                    LedgerEntry::create([
                        'customer_id' => $customer->id,
                        'ref_type' => 'Invoice',
                        'ref_id' => $invoice->id,
                        'type' => 'credit',
                        'amount' => $paidAmount,
                        'description' => $invoice->number . ' nolu fatura ödemesi',
                        'occurred_at' => $paidAt,
                    ]);
                } else {
                    // Add debit entry for unpaid invoice
                    LedgerEntry::create([
                        'customer_id' => $customer->id,
                        'ref_type' => 'Invoice',
                        'ref_id' => $invoice->id,
                        'type' => 'debit',
                        'amount' => $invoice->grand_total,
                        'description' => $invoice->number . ' nolu fatura tutarı',
                        'occurred_at' => $issueDate,
                    ]);
                }
            }

            // 3 Quotes per month
            for ($j = 0; $j < 3; $j++) {
                $status = ['pending', 'accepted', 'rejected'][rand(0, 2)];
                // Note: 'pending' is not in our enum in migration, let's fix to 'draft' or 'sent'
                $realStatus = $status === 'pending' ? 'sent' : ($status === 'rejected' ? 'expired' : $status);

                Quote::create([
                    'customer_id' => $customers[array_rand($customers)]->id,
                    'number' => 'QO-' . $monthDate->format('Ym') . str_pad($j + 1, 3, '0', STR_PAD_LEFT),
                    'valid_until' => $monthDate->copy()->addDays(rand(20, 30)),
                    'status' => $realStatus,
                    'grand_total' => rand(500, 5000),
                    'currency' => 'TRY',
                    'created_at' => $monthDate->copy()->addDays(rand(1, 15)),
                ]);
            }
        }

        $this->command->info('✓ Production Demo Seeding Completed!');
    }
}

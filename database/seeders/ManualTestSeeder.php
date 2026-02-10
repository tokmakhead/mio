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

class ManualTestSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Clearing all system data for manual testing...');

        // Disable foreign key checks (SQLite & MySQL compatible)
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // MySQL
        } catch (\Exception $e) {
            DB::statement('PRAGMA foreign_keys = ON;'); // SQLite fallback
        }

        // Truncate all tables
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

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // MySQL
        } catch (\Exception $e) {
            // SQLite defaults are usually fine
        }

        // Create License
        $this->command->info('Creating license...');
        License::create([
            'license_key' => 'MIONEX-TEST-MANUAL',
            'client_name' => 'Manual Tester',
            'domain' => 'mionex.test',
            'status' => 'active',
            'expires_at' => Carbon::now()->addYears(10),
            'last_check_at' => Carbon::now(),
        ]);

        // Create Admin User
        $this->command->info('Creating admin user...');
        User::create([
            'name' => 'Mionex Admin',
            'email' => 'admin@mionex.test',
            'password' => Hash::make('mionex123'),
            'role' => 'admin',
            'demo_readonly' => false,
        ]);

        // Create Demo User
        $this->command->info('Creating demo user...');
        User::create([
            'name' => 'Demo Kullanıcı',
            'email' => 'demo@mionex.test',
            'password' => Hash::make('demo123'),
            'role' => 'user',
            'demo_readonly' => true,
        ]);

        $this->command->info('✓ System cleared and ready for manual testing!');
    }
}

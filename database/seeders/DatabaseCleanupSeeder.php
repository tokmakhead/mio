<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseCleanupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'invoice_items',
            'invoices',
            'quote_items',
            'quotes',
            'services',
            'customers',
            'providers',
            'payments',
            'ledger_entries',
            'activity_logs',
            'email_logs',
            'bank_accounts'
        ];

        foreach ($tables as $table) {
            try {
                DB::table($table)->delete();
                $this->command->info("Table cleaned: {$table}");
            } catch (\Exception $e) {
                $this->command->error("Error cleaning table {$table}: " . $e->getMessage());
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info('Database cleanup completed successfully!');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class UpdateInvoiceStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vadesi geçen faturaların durumlarını otomatik olarak "overdue" yapar.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updatedCount = 0;

        $invoices = Invoice::whereNotIn('status', ['paid', 'cancelled', 'draft'])
            ->get();

        foreach ($invoices as $invoice) {
            /** @var Invoice $invoice */
            $oldStatus = $invoice->status;
            $invoice->updateStatus();

            if ($invoice->status !== $oldStatus) {
                if ($invoice->status === 'overdue') {
                    $invoice->logActivity('overdue');
                }
                $updatedCount++;
            }
        }

        $this->info("Toplam {$updatedCount} faturanın durumu güncellendi.");
    }
}

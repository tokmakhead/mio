<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        if ((float) $invoice->grand_total > 0) {
            $this->syncToLedger($invoice);
        }
        $this->clearDashboardCache();
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        $this->syncToLedger($invoice);
        $this->clearDashboardCache();
    }

    /**
     * Internal method to create or update ledger entry
     */
    private function syncToLedger(Invoice $invoice): void
    {
        $ledgerEntry = $invoice->customer->ledgerEntries()
            ->where('ref_type', Invoice::class)
            ->where('ref_id', $invoice->id)
            ->first();

        if ((float) $invoice->grand_total <= 0) {
            if ($ledgerEntry) {
                $ledgerEntry->delete();
            }
            return;
        }

        $data = [
            'type' => 'debit',
            'amount' => $invoice->grand_total,
            'currency' => $invoice->currency,
            'ref_type' => Invoice::class,
            'ref_id' => $invoice->id,
            'occurred_at' => \Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d'),
            'description' => "Fatura #{$invoice->number}",
        ];

        if ($ledgerEntry) {
            $ledgerEntry->update($data);
        } else {
            $invoice->customer->ledgerEntries()->create($data);
        }
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(\App\Models\Invoice $invoice): void
    {
        $invoice->customer->ledgerEntries()
            ->where('ref_type', \App\Models\Invoice::class)
            ->where('ref_id', $invoice->id)
            ->delete();
        $this->clearDashboardCache();
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Clear dashboard cache for supported currencies
     */
    private function clearDashboardCache(): void
    {
        $currencies = ['TRY', 'USD', 'EUR', 'GBP'];
        foreach ($currencies as $currency) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats_' . $currency);
        }
    }
}

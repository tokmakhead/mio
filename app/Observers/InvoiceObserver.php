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
        $invoice->customer->ledgerEntries()->create([
            'type' => 'debit',
            'amount' => $invoice->grand_total,
            'currency' => $invoice->currency,
            'ref_type' => Invoice::class,
            'ref_id' => $invoice->id,
            'occurred_at' => \Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d'),
            'description' => "Fatura #{$invoice->number}",
        ]);
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(\App\Models\Invoice $invoice): void
    {
        $ledgerEntry = $invoice->customer->ledgerEntries()
            ->where('ref_type', \App\Models\Invoice::class)
            ->where('ref_id', $invoice->id)
            ->first();

        if ($ledgerEntry) {
            $ledgerEntry->update([
                'amount' => $invoice->grand_total,
                'currency' => $invoice->currency,
                'occurred_at' => \Carbon\Carbon::parse($invoice->issue_date)->format('Y-m-d'),
                'description' => "Fatura #{$invoice->number} (Güncellendi)",
            ]);
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
}

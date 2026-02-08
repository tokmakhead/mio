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
            'occurred_at' => is_string($invoice->issue_date) ? $invoice->issue_date : $invoice->issue_date->format('Y-m-d'),
            'description' => "Fatura #{$invoice->number}",
        ]);
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
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

<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        // 1. Ledger Entry (Credit)
        $payment->customer->ledgerEntries()->create([
            'type' => 'credit',
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'ref_type' => Payment::class,
            'ref_id' => $payment->id,
            'occurred_at' => $payment->paid_at ?? now(),
            'description' => "Ödeme (Fatura #{$payment->invoice->number})",
        ]);

        // 2. Update Invoice
        $invoice = $payment->invoice;
        $invoice->increment('paid_amount', $payment->amount);
        $invoice->refresh()->updateStatus();

        if ($invoice->status === 'paid') {
            $invoice->logActivity('paid');

            // Auto-extend services
            foreach ($invoice->items as $item) {
                if ($item->service) {
                    $service = $item->service;
                    $months = match ($service->cycle) {
                        'monthly' => 1,
                        'quarterly' => 3,
                        'yearly' => 12,
                        'biennial' => 24,
                        default => 0
                    };

                    if ($months > 0) {
                        $currentEnd = $service->end_date ?? now();
                        // If it's already past, start from now
                        if ($currentEnd->isPast())
                            $currentEnd = now();

                        $service->update([
                            'end_date' => $currentEnd->addMonths($months),
                            'status' => 'active'
                        ]);
                    }
                }
            }
        }
        $this->clearDashboardCache();
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(\App\Models\Payment $payment): void
    {
        // 1. Update Ledger Entry
        $ledgerEntry = $payment->customer->ledgerEntries()
            ->where('ref_type', \App\Models\Payment::class)
            ->where('ref_id', $payment->id)
            ->first();

        if ($ledgerEntry) {
            $ledgerEntry->update([
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'occurred_at' => $payment->paid_at ?? now(),
                'description' => "Ödeme (Fatura #{$payment->invoice->number}) [Güncellendi]",
            ]);
        }

        // 2. Adjust Invoices if amount or invoice_id changed
        if ($payment->wasChanged(['amount', 'invoice_id'])) {
            $oldAmount = $payment->getOriginal('amount');
            $oldInvoiceId = $payment->getOriginal('invoice_id');

            if ($oldInvoiceId == $payment->invoice_id) {
                // Same invoice, just amount changed
                $diff = $payment->amount - $oldAmount;
                $invoice = $payment->invoice;
                $invoice->increment('paid_amount', $diff);
                $invoice->refresh()->updateStatus();
            } else {
                // Moved to a different invoice
                $oldInvoice = \App\Models\Invoice::find($oldInvoiceId);
                if ($oldInvoice) {
                    $oldInvoice->decrement('paid_amount', $oldAmount);
                    $oldInvoice->updateStatus();
                }

                $newInvoice = $payment->invoice;
                $newInvoice->increment('paid_amount', $payment->amount);
                $newInvoice->refresh()->updateStatus();
            }
        }
        $this->clearDashboardCache();
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(\App\Models\Payment $payment): void
    {
        // 1. Delete Ledger Entry
        $payment->customer->ledgerEntries()
            ->where('ref_type', \App\Models\Payment::class)
            ->where('ref_id', $payment->id)
            ->delete();

        // 2. Decrement Invoice Paid Amount
        $invoice = $payment->invoice;
        if ($invoice) {
            $invoice->decrement('paid_amount', $payment->amount);
            $invoice->refresh()->updateStatus();
        }
        $this->clearDashboardCache();
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
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

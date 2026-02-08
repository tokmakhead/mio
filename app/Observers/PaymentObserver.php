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
        $invoice->paid_amount += $payment->amount;
        $invoice->updateStatus(); // This will save the invoice

        if ($invoice->status === 'paid') {
            $invoice->logActivity('paid');
        }
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
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
}

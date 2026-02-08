<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_creation_creates_debit_ledger_entry()
    {
        $customer = \App\Models\Customer::factory()->create();
        $invoice = \App\Models\Invoice::factory()->create([
            'customer_id' => $customer->id,
            'grand_total' => 1000,
            'currency' => 'TRY',
            'issue_date' => now(),
            'due_date' => now()->addDays(7),
        ]);

        $this->assertDatabaseHas('ledger_entries', [
            'customer_id' => $customer->id,
            'type' => 'debit',
            'amount' => 1000,
            'currency' => 'TRY',
            'ref_type' => \App\Models\Invoice::class,
            'ref_id' => $invoice->id,
        ]);

        $this->assertEquals(1000, $customer->refresh()->balance);
    }

    public function test_payment_creation_updates_invoice_and_creates_credit_ledger_entry()
    {
        $customer = \App\Models\Customer::factory()->create();
        $invoice = \App\Models\Invoice::factory()->create([
            'customer_id' => $customer->id,
            'grand_total' => 1000,
            'paid_amount' => 0,
            'status' => 'sent',
        ]);

        // Create Payment via Controller (as a user might)
        // Or directly via Model to test Observer
        $payment = \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => 500,
            'currency' => 'TRY',
            'method' => 'cash',
            'paid_at' => now(),
        ]);

        // Check Ledger
        $this->assertDatabaseHas('ledger_entries', [
            'customer_id' => $customer->id,
            'type' => 'credit',
            'amount' => 500,
            'ref_type' => \App\Models\Payment::class,
            'ref_id' => $payment->id,
        ]);

        // Check Invoice Status Update
        $invoice->refresh();
        $this->assertEquals(500, $invoice->paid_amount);
        $this->assertEquals('sent', $invoice->status); // Still partial

        // Check Balance (1000 debit - 500 credit = 500)
        $this->assertEquals(500, $customer->refresh()->balance);
    }

    public function test_full_payment_updates_invoice_status_to_paid()
    {
        $invoice = \App\Models\Invoice::factory()->create([
            'grand_total' => 1000,
            'paid_amount' => 0,
            'status' => 'sent',
        ]);

        \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_id,
            'amount' => 1000,
            'currency' => 'TRY',
            'method' => 'bank',
            'paid_at' => now(),
        ]);

        $this->assertEquals('paid', $invoice->refresh()->status);
        $this->assertNotNull($invoice->paid_at);
        $this->assertEquals(0, $invoice->customer->refresh()->balance);
    }
}

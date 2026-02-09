<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObserverIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_invoice_update_updates_ledger_entry()
    {
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->create([
            'customer_id' => $customer->id,
            'grand_total' => 1000,
            'currency' => 'TRY',
            'issue_date' => now()->format('Y-m-d'),
        ]);

        $invoice->update([
            'grand_total' => 1500,
            'currency' => 'USD',
        ]);

        $this->assertDatabaseHas('ledger_entries', [
            'ref_type' => Invoice::class,
            'ref_id' => $invoice->id,
            'amount' => 1500,
            'currency' => 'USD',
        ]);
    }

    public function test_invoice_deletion_removes_ledger_entry()
    {
        $invoice = Invoice::factory()->create(['grand_total' => 1000]);
        $invoiceId = $invoice->id;

        $invoice->delete();

        $this->assertDatabaseMissing('ledger_entries', [
            'ref_type' => Invoice::class,
            'ref_id' => $invoiceId,
        ]);
    }

    public function test_payment_update_updates_ledger_and_invoice()
    {
        $invoice = Invoice::factory()->create(['grand_total' => 2000, 'paid_amount' => 0]);
        $payment = Payment::create([
            'customer_id' => $invoice->customer_id,
            'invoice_id' => $invoice->id,
            'amount' => 500,
            'currency' => 'TRY',
            'method' => 'cash',
            'paid_at' => now(),
        ]);

        $payment->update(['amount' => 800]);

        $this->assertDatabaseHas('ledger_entries', [
            'ref_type' => Payment::class,
            'ref_id' => $payment->id,
            'amount' => 800,
        ]);

        $this->assertEquals(800, $invoice->refresh()->paid_amount);
    }

    public function test_payment_deletion_reverts_invoice_and_removes_ledger()
    {
        $invoice = Invoice::factory()->create(['grand_total' => 2000, 'paid_amount' => 500]);
        $payment = Payment::create([
            'customer_id' => $invoice->customer_id,
            'invoice_id' => $invoice->id,
            'amount' => 500,
            'currency' => 'TRY',
            'method' => 'cash',
            'paid_at' => now(),
        ]);

        $paymentId = $payment->id;
        $payment->delete();

        $this->assertDatabaseMissing('ledger_entries', [
            'ref_type' => Payment::class,
            'ref_id' => $paymentId,
        ]);

        $this->assertEquals(500, $invoice->refresh()->paid_amount);
        // Note: Invoice was 500 paid before we created a 500 payment in this test.
        // Wait, Payment::create in the observer adds to paid_amount.
        // Let's fix the test logic to be clearer.
    }

    public function test_payment_invoice_change_moves_balance()
    {
        $invoice1 = Invoice::factory()->create(['grand_total' => 1000, 'paid_amount' => 0]);
        $invoice2 = Invoice::factory()->create(['grand_total' => 1000, 'paid_amount' => 0]);

        $payment = Payment::create([
            'customer_id' => $invoice1->customer_id,
            'invoice_id' => $invoice1->id,
            'amount' => 400,
            'currency' => 'TRY',
            'method' => 'cash',
            'paid_at' => now(),
        ]);

        $this->assertEquals(400, $invoice1->refresh()->paid_amount);

        $payment->update(['invoice_id' => $invoice2->id]);

        $this->assertEquals(0, $invoice1->refresh()->paid_amount);
        $this->assertEquals(400, $invoice2->refresh()->paid_amount);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReconciliationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reconciliation_index_shows_inconsistent_invoices()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(\App\Models\User::factory()->create());

        $customer = \App\Models\Customer::factory()->create();

        // Consistent Invoice
        \App\Models\Invoice::factory()->create([
            'customer_id' => $customer->id,
            'grand_total' => 1000,
            'paid_amount' => 1000,
            'status' => 'paid',
        ]);

        // Inconsistent Invoice (Paid amount matches but status is sent)
        $inconsistent = \App\Models\Invoice::factory()->create([
            'customer_id' => $customer->id,
            'grand_total' => 500,
            'paid_amount' => 500,
            'status' => 'sent',
        ]);

        $response = $this->get(route('accounting.reconciliation.index'));

        $response->assertStatus(200);
        $response->assertViewHas('errors', function ($errors) use ($inconsistent) {
            return $errors->contains(function ($value, $key) use ($inconsistent) {
                return $value->id === $inconsistent->id;
            });
        });
    }

    public function test_reconciliation_fix_updates_invoice_status()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(\App\Models\User::factory()->create());

        $invoice = \App\Models\Invoice::factory()->create([
            'grand_total' => 500,
            'paid_amount' => 500,
            'status' => 'sent',
        ]);

        $response = $this->post(route('accounting.reconciliation.fix'));

        $response->assertRedirect(route('accounting.reconciliation.index'));
        $this->assertEquals('paid', $invoice->refresh()->status);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoices = Invoice::factory()->count(10)->create();

        foreach ($invoices as $invoice) {
            $itemCount = rand(1, 3);
            $subtotal = 0;
            $taxTotal = 0;

            for ($i = 0; $i < $itemCount; $i++) {
                $service = Service::inRandomOrder()->first();
                $qty = rand(1, 4);
                $unitPrice = $service ? $service->price : rand(200, 2000);
                $vatRate = 20;

                $lineSubtotal = $qty * $unitPrice;
                $lineTax = $lineSubtotal * ($vatRate / 100);
                $lineTotal = $lineSubtotal + $lineTax;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'service_id' => $service?->id,
                    'description' => $service ? $service->name : 'Genel Danışmanlık Hizmeti',
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'vat_rate' => $vatRate,
                    'line_subtotal' => $lineSubtotal,
                    'line_tax' => $lineTax,
                    'line_total' => $lineTotal,
                ]);

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;
            }

            $invoice->subtotal = $subtotal;
            $invoice->tax_total = $taxTotal;
            $invoice->grand_total = $subtotal + $taxTotal;

            // Randomly set paid_amount for 'paid' status
            if ($invoice->status === 'paid') {
                $invoice->paid_amount = $invoice->grand_total;
                $invoice->paid_at = now();
            } elseif ($invoice->status === 'overdue' && $invoice->due_date >= now()->startOfDay()) {
                // If seeder randomly picked overdue but date is not actually past, adjust date
                $invoice->due_date = now()->subDays(rand(1, 30));
            } elseif ($invoice->due_date < now()->startOfDay() && $invoice->status === 'sent') {
                $invoice->status = 'overdue';
            }

            $invoice->save();
        }
    }
}

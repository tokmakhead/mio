<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Service;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quotes = Quote::factory()->count(10)->create();

        foreach ($quotes as $quote) {
            $itemCount = rand(1, 4);
            $subtotal = 0;
            $taxTotal = 0;

            for ($i = 0; $i < $itemCount; $i++) {
                $service = Service::inRandomOrder()->first();
                $qty = rand(1, 4);
                $unitPrice = $service ? $service->price : rand(100, 5000);
                $vatRate = 20;

                $lineSubtotal = $qty * $unitPrice;
                $lineTax = $lineSubtotal * ($vatRate / 100);
                $lineTotal = $lineSubtotal + $lineTax;

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'service_id' => $service?->id,
                    'description' => $service ? $service->name : 'Özel Hizmet ' . ($i + 1),
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

            $quote->subtotal = $subtotal;
            $quote->tax_total = $taxTotal;
            $quote->grand_total = $subtotal + $taxTotal - $quote->discount_total;
            $quote->save();
        }
    }
}

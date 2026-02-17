<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\BrandSetting;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QuotePdfService
{
    public function prepareData(Quote $quote)
    {
        $quote->load(['customer', 'items.service']);
        $brandSettings = BrandSetting::all()->pluck('value', 'key');

        // Calculate Totals
        $totals = $this->calculateTotals($quote);

        // Generate QR Code (Base64)
        $qrBase64 = $this->generateQrCode($quote);

        // Format Date
        $dates = [
            'issue_date' => $quote->created_at ? $quote->created_at->format('d.m.Y') : now()->format('d.m.Y'),
            'due_date' => $quote->valid_until ? $quote->valid_until->format('d.m.Y') : '-',
        ];

        return [
            'quote' => $quote,
            'brandSettings' => $brandSettings,
            'totals' => $totals,
            'dates' => $dates,
            'qrBase64' => $qrBase64
        ];
    }

    protected function calculateTotals(Quote $quote)
    {
        return [
            'subtotal' => number_format((float) $quote->subtotal, 2),
            'tax_total' => number_format((float) $quote->tax_total, 2),
            'discount_total' => number_format((float) ($quote->discount_total ?? 0), 2),
            'grand_total' => number_format((float) $quote->grand_total, 2),
            'currency' => $quote->currency,
        ];
    }

    protected function generateQrCode(Quote $quote)
    {
        try {
            // Check for simple-qrcode package
            if (class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                // For quotes, we might not have a public show route without auth, 
                // but we can encode the Quote Number and Amount for verification.
                $data = "Quote: {$quote->number} | Amount: {$quote->grand_total} {$quote->currency}";

                // If there is a public preview link (theoretical), we could use that.
                // For now, let's use the internal show link or just data.
                // $data = route('quotes.show', $quote->id); 

                $png = QrCode::format('png')->size(100)->generate($data);
                return 'data:image/png;base64,' . base64_encode($png);
            }

            return null;

        } catch (\Exception $e) {
            return null;
        }
    }
}

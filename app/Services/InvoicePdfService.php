<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\BrandSetting;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoicePdfService
{
    public function prepareData(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.service']);
        $brandSettings = BrandSetting::all()->pluck('value', 'key');

        // Calculate Totals
        $totals = $this->calculateTotals($invoice);

        // Generate QR Code (Base64)
        $qrBase64 = $this->generateQrCode($invoice);

        // Format Date
        $dates = [
            'issue_date' => $invoice->issue_date->format('d.m.Y'),
            'due_date' => $invoice->due_date->format('d.m.Y'),
        ];

        return [
            'invoice' => $invoice,
            'brandSettings' => $brandSettings,
            'totals' => $totals,
            'dates' => $dates,
            'qrBase64' => $qrBase64
        ];
    }

    protected function calculateTotals(Invoice $invoice)
    {
        return [
            'subtotal' => number_format($invoice->subtotal, 2),
            'tax_total' => number_format($invoice->tax_total, 2),
            'discount_total' => number_format($invoice_discount_total ?? 0, 2),
            'grand_total' => number_format($invoice->grand_total, 2),
            'remaining_amount' => number_format($invoice->remaining_amount, 2),
            'currency' => $invoice->currency,
        ];
    }

    protected function generateQrCode(Invoice $invoice)
    {
        try {
            // Using a public API fallback if local generation fails (since user might not have extension)
            // But aiming for local simple-qrcode usage as requested:
            if (class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                $png = QrCode::format('png')->size(100)->generate(route('invoices.show', $invoice->id));
                return 'data:image/png;base64,' . base64_encode($png);
            }

            // Fallback to API if package not installed
            return 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . route('invoices.show', $invoice->id);

        } catch (\Exception $e) {
            return null;
        }
    }
}

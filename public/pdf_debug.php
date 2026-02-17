<?php

use App\Models\Invoice;
use App\Services\InvoicePdfService;
use Illuminate\Support\Facades\View;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Manually fetch an invoice (ID 1 or first available)
$invoice = Invoice::first();

if (!$invoice) {
    die("Hiç fatura bulunamadı. Lütfen önce fatura oluşturun.");
}

// Prepare Data
$invoice->load(['customer', 'items.service']);
$brandSettings = \App\Models\BrandSetting::all()->pluck('value', 'key');
$dates = [
    'issue_date' => $invoice->issue_date->format('d.m.Y'),
    'due_date' => $invoice->due_date->format('d.m.Y'),
];

// Calculate Totals Simple Version
$totals = [
    'subtotal' => number_format($invoice->subtotal, 2),
    'tax_total' => number_format($invoice->tax_total, 2),
    'discount_total' => number_format($invoice->discount_total ?? 0, 2),
    'grand_total' => number_format($invoice->grand_total, 2),
    'remaining_amount' => number_format($invoice->remaining_amount, 2),
    'currency' => $invoice->currency,
];

// Check fonts
$fonts = [
    'Regular' => file_exists(__DIR__ . '/fonts/Inter-Regular.ttf'),
    'Medium' => file_exists(__DIR__ . '/fonts/Inter-Medium.ttf'),
    'Bold' => file_exists(__DIR__ . '/fonts/Inter-Bold.ttf'),
];

// Generic QR
$qrBase64 = null;

echo "<!-- DEBUG INFO: Fonts Found: " . json_encode($fonts) . " -->";

// Render View
echo view('invoices.premium', [
    'invoice' => $invoice,
    'brandSettings' => $brandSettings,
    'totals' => $totals,
    'dates' => $dates,
    'qrBase64' => $qrBase64
])->render();

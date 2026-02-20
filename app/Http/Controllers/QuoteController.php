<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Quote::with('customer');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotes = $query->latest()->paginate(15);

        // KPIs (Counts)
        $totalQuotes = Quote::count();
        $draftCount = Quote::where('status', 'draft')->count();
        $sentCount = Quote::where('status', 'sent')->count();
        $acceptedCount = Quote::where('status', 'accepted')->count();

        // KPIs (Financials)
        $rawFinancials = Quote::select('currency', 'status', DB::raw('SUM(grand_total) as total'))
            ->groupBy('currency', 'status')
            ->get();

        $financials = [];
        foreach ($rawFinancials as $row) {
            $financials[$row->currency][$row->status] = $row->total;
            if (!isset($financials[$row->currency]['total']))
                $financials[$row->currency]['total'] = 0;
            $financials[$row->currency]['total'] += $row->total;
        }

        $defaultCurrency = \App\Models\SystemSetting::first()->default_currency ?? 'TRY';

        return view('quotes.index', compact('quotes', 'totalQuotes', 'draftCount', 'sentCount', 'acceptedCount', 'financials', 'defaultCurrency'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::active()->orderBy('name')->get();

        return view('quotes.create', compact('customers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuoteRequest $request)
    {
        return DB::transaction(function () use ($request) {
            // Generate robust sequential number
            $number = Quote::generateNumber();

            $quote = Quote::create([
                'customer_id' => $request->customer_id,
                'number' => $number,
                'status' => 'draft',
                'currency' => $request->currency,
                'discount_type' => $request->discount_type,
                'discount_rate' => $request->discount_rate ?? 0,
                'valid_until' => $request->valid_until,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $itemData) {
                $subtotal = $itemData['qty'] * $itemData['unit_price'];
                $tax = $subtotal * ($itemData['vat_rate'] / 100);

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'service_id' => $itemData['service_id'] ?? null,
                    'description' => $itemData['description'],
                    'qty' => $itemData['qty'],
                    'unit_price' => $itemData['unit_price'],
                    'vat_rate' => $itemData['vat_rate'],
                    'line_subtotal' => $subtotal,
                    'line_tax' => $tax,
                    'line_total' => $subtotal + $tax,
                ]);
            }

            $quote->calculateTotals();

            return redirect()->route('quotes.show', $quote)
                ->with('success', 'Teklif başarıyla oluşturuldu.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        $quote->load(['customer', 'items.service']);
        return view('quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        // For simplicity, we might only allow editing drafts
        if ($quote->status !== 'draft') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Sadece taslak durumundaki teklifler düzenlenebilir.');
        }

        $customers = Customer::orderBy('name')->get();
        $services = Service::active()->orderBy('name')->get();
        $quote->load('items');

        return view('quotes.edit', compact('quote', 'customers', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreQuoteRequest $request, Quote $quote)
    {
        return DB::transaction(function () use ($request, $quote) {
            $quote->update([
                'customer_id' => $request->customer_id,
                'currency' => $request->currency,
                'discount_type' => $request->discount_type,
                'discount_rate' => $request->discount_rate ?? 0,
                'valid_until' => $request->valid_until,
                'notes' => $request->notes,
            ]);

            // Sync items (simple way: delete and recreate)
            $quote->items()->delete();

            foreach ($request->items as $itemData) {
                $subtotal = $itemData['qty'] * $itemData['unit_price'];
                $tax = $subtotal * ($itemData['vat_rate'] / 100);

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'service_id' => $itemData['service_id'] ?? null,
                    'description' => $itemData['description'],
                    'qty' => $itemData['qty'],
                    'unit_price' => $itemData['unit_price'],
                    'vat_rate' => $itemData['vat_rate'],
                    'line_subtotal' => $subtotal,
                    'line_tax' => $tax,
                    'line_total' => $subtotal + $tax,
                ]);
            }

            $quote->calculateTotals();

            return redirect()->route('quotes.show', $quote)
                ->with('success', 'Teklif başarıyla güncellendi.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Teklif başarıyla silindi.');
    }

    public function pdf(Quote $quote, \App\Services\QuotePdfService $pdfService)
    {
        $data = $pdfService->prepareData($quote);
        $pdf = Pdf::loadView('quotes.premium', $data);
        return $pdf->download($quote->number . '.pdf');
    }

    public function previewPdf(Quote $quote, \App\Services\QuotePdfService $pdfService)
    {
        $data = $pdfService->prepareData($quote);
        return view('quotes.premium', $data);
    }

    /**
     * Send quote via email
     */
    public function send(Quote $quote)
    {
        $quote->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $quote->logActivity('sent');

        // Trigger real email
        if ($quote->customer_id && filter_var($quote->customer->email, FILTER_VALIDATE_EMAIL)) {
            try {
                $settings = \App\Models\EmailSetting::first();
                $useQueue = $settings && $settings->use_queue;
                $mailable = new \App\Mail\QuoteMail($quote);

                if ($useQueue) {
                    \Illuminate\Support\Facades\Mail::to($quote->customer->email)->queue($mailable);
                    \App\Models\EmailLog::create([
                        'to' => $quote->customer->email,
                        'subject' => 'Teklif #' . $quote->number,
                        'body' => 'Queued for sending',
                        'status' => 'queued',
                        'sent_at' => now(),
                    ]);
                } else {
                    \Illuminate\Support\Facades\Mail::to($quote->customer->email)->sendNow($mailable);
                }
            } catch (\Exception $e) {
                \App\Models\EmailLog::create([
                    'to' => $quote->customer->email,
                    'subject' => 'Teklif #' . $quote->number,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'sent_at' => now(),
                ]);
                return back()->with('warning', 'Teklif durumu güncellendi ancak e-posta gönderilemedi: ' . $e->getMessage());
            }
        } else {
            return back()->with('warning', 'Teklif durumu güncellendi ancak geçerli bir müşteri e-posta adresi bulunamadığı için e-posta gönderilemedi.');
        }

        return back()->with('success', 'Teklif başarıyla gönderildi.');
    }

    /**
     * Accept quote
     */
    public function accept(Quote $quote)
    {
        $quote->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $quote->logActivity('accepted');

        return back()->with('success', 'Teklif kabul edildi.');
    }

    /**
     * Convert to Invoice
     */
    public function convertToInvoice(Quote $quote)
    {
        if ($quote->status !== 'accepted') {
            return back()->with('error', 'Sadece kabul edilen teklifler faturaya dönüştürülebilir.');
        }

        return DB::transaction(function () use ($quote) {
            // Check if already converted
            $existingInvoice = \App\Models\Invoice::where('quote_id', $quote->id)->first();
            if ($existingInvoice) {
                return redirect()->route('invoices.show', $existingInvoice)
                    ->with('info', 'Bu teklif zaten faturaya dönüştürülmüş.');
            }

            // Generate robust sequential invoice number
            $number = \App\Models\Invoice::generateNumber();

            $invoice = \App\Models\Invoice::create([
                'customer_id' => $quote->customer_id,
                'quote_id' => $quote->id,
                'number' => $number,
                'status' => 'sent',
                'currency' => $quote->currency,
                'discount_type' => $quote->discount_type ?? 'fixed',
                'discount_rate' => $quote->discount_rate ?? 0,
                'discount_total' => $quote->discount_total,
                'subtotal' => $quote->subtotal,
                'tax_total' => $quote->tax_total,
                'grand_total' => $quote->grand_total,
                'issue_date' => now(),
                'due_date' => now()->addDays(7),
                'notes' => $quote->notes,
            ]);

            foreach ($quote->items as $item) {
                \App\Models\InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'service_id' => $item->service_id,
                    'description' => $item->description,
                    'qty' => $item->qty,
                    'unit_price' => $item->unit_price,
                    'vat_rate' => $item->vat_rate,
                    'line_subtotal' => $item->line_subtotal,
                    'line_tax' => $item->line_tax,
                    'line_total' => $item->line_total,
                ]);
            }

            $quote->update(['status' => 'invoiced']);
            $quote->logActivity('converted', ['invoice_id' => $invoice->id]);

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Teklif başarıyla faturaya dönüştürüldü.');
        });
    }

    public function bulkExport(Request $request, \App\Services\QuotePdfService $pdfService)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:quotes,id',
        ]);

        $quotes = Quote::whereIn('id', $request->ids)->get();

        if ($quotes->isEmpty()) {
            return back()->with('error', 'Teklif seçilmedi.');
        }

        $zip = new \ZipArchive;
        $fileName = 'teklifler_' . now()->format('Y-m-d_H-i') . '.zip';
        $path = storage_path('app/public/' . $fileName);

        if ($zip->open($path, \ZipArchive::CREATE) === TRUE) {
            foreach ($quotes as $quote) {
                $data = $pdfService->prepareData($quote);
                $pdf = Pdf::loadView('quotes.premium', $data);
                $content = $pdf->output();
                $zip->addFromString($quote->number . '.pdf', $content);
            }
            $zip->close();
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('customer');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('customer', function ($cq) use ($request) {
                        $cq->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest('issue_date')->paginate(15);

        // KPIs
        $totalInvoices = Invoice::count();
        $totalAmount = Invoice::sum('grand_total');
        $totalCollected = Invoice::sum('paid_amount');
        $totalPending = $totalAmount - $totalCollected;

        return view('invoices.index', compact(
            'invoices',
            'totalInvoices',
            'totalAmount',
            'totalCollected',
            'totalPending'
        ));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::all();

        return view('invoices.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'currency' => 'required|in:TRY,USD,EUR',
            'discount_total' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.vat_rate' => 'required|integer',
        ]);

        return DB::transaction(function () use ($validated) {
            // Generate robust sequential number
            $number = Invoice::generateNumber();

            $invoice = Invoice::create([
                'customer_id' => $validated['customer_id'],
                'number' => $number,
                'status' => 'draft',
                'currency' => $validated['currency'],
                'discount_total' => $validated['discount_total'] ?? 0,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $itemData) {
                $lineSubtotal = $itemData['qty'] * $itemData['unit_price'];
                $lineTax = $lineSubtotal * ($itemData['vat_rate'] / 100);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'service_id' => $itemData['service_id'] ?? null,
                    'description' => $itemData['description'],
                    'qty' => $itemData['qty'],
                    'unit_price' => $itemData['unit_price'],
                    'vat_rate' => $itemData['vat_rate'],
                    'line_subtotal' => $lineSubtotal,
                    'line_tax' => $lineTax,
                    'line_total' => $lineSubtotal + $lineTax,
                ]);
            }

            $invoice->calculateTotals();

            return redirect()->route('invoices.index')
                ->with('success', 'Fatura başarıyla oluşturuldu.');
        });
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.service']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Ödenmiş faturalar düzenlenemez.');
        }

        $customers = Customer::orderBy('name')->get();
        $services = Service::all();
        $invoice->load('items');

        return view('invoices.edit', compact('invoice', 'customers', 'services'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Ödenmiş faturalar düzenlenemez.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'currency' => 'required|in:TRY,USD,EUR',
            'discount_total' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.vat_rate' => 'required|integer',
        ]);

        return DB::transaction(function () use ($validated, $invoice) {
            $invoice->update([
                'customer_id' => $validated['customer_id'],
                'currency' => $validated['currency'],
                'discount_total' => $validated['discount_total'] ?? 0,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'notes' => $validated['notes'],
            ]);

            $invoice->items()->delete();

            foreach ($validated['items'] as $itemData) {
                $lineSubtotal = $itemData['qty'] * $itemData['unit_price'];
                $lineTax = $lineSubtotal * ($itemData['vat_rate'] / 100);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'service_id' => $itemData['service_id'] ?? null,
                    'description' => $itemData['description'],
                    'qty' => $itemData['qty'],
                    'unit_price' => $itemData['unit_price'],
                    'vat_rate' => $itemData['vat_rate'],
                    'line_subtotal' => $lineSubtotal,
                    'line_tax' => $lineTax,
                    'line_total' => $lineSubtotal + $lineTax,
                ]);
            }

            $invoice->calculateTotals();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Fatura başarıyla güncellendi.');
        });
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Ödenmiş faturalar silinemez.');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Fatura silindi.');
    }

    public function pdf(Invoice $invoice, \App\Services\InvoicePdfService $pdfService)
    {
        $data = $pdfService->prepareData($invoice);
        $pdf = Pdf::loadView('invoices.premium', $data);
        return $pdf->download($invoice->number . '.pdf');
    }

    public function previewPdf(Invoice $invoice, \App\Services\InvoicePdfService $pdfService)
    {
        $data = $pdfService->prepareData($invoice);
        return view('invoices.premium', $data);
    }

    public function send(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $invoice->logActivity('sent');

        return back()->with('success', 'Fatura müşteriye gönderildi olarak işaretlendi.');
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . ($invoice->grand_total - $invoice->paid_amount),
            'method' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_id,
            'amount' => $validated['amount'],
            'currency' => $invoice->currency,
            'method' => $validated['method'] ?? 'cash',
            'paid_at' => now(),
            'note' => $validated['note'] ?? null,
        ]);

        // Note: PaymentObserver handles updating $invoice->paid_amount and status

        return back()->with('success', 'Ödeme başarıyla kaydedildi.');
    }
}

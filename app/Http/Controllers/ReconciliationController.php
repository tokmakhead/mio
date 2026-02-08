<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReconciliationController extends Controller
{
    /**
     * Display the reconciliation dashboard.
     */
    public function index(Request $request)
    {
        // 1. Inconsistent Invoices (Errors)
        $invoiceQuery = Invoice::with('customer');

        if ($request->filled('start_date')) {
            $invoiceQuery->where('issue_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $invoiceQuery->where('issue_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $invoiceQuery->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('number', 'like', '%' . $request->search . '%');
        }

        $invoices = $invoiceQuery->get();

        $errors = $invoices->filter(function ($invoice) {
            $paidAmount = (float) $invoice->paid_amount;
            $grandTotal = (float) $invoice->grand_total;
            $isPaid = $paidAmount >= $grandTotal;
            $statusIsPaid = $invoice->status === 'paid';

            if ($isPaid && !$statusIsPaid)
                return true;
            if (!$isPaid && $statusIsPaid)
                return true;
            if ($paidAmount > $grandTotal)
                return true;
            return false;
        });

        $consistent = $invoices->diff($errors);

        // 2. Customer Balances
        $customerQuery = Customer::with(['ledgerEntries', 'invoices']);

        if ($request->filled('search')) {
            $customerQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $customers = $customerQuery->get()
            ->map(function ($customer) {
                $debits = $customer->ledgerEntries->where('type', 'debit')->sum('amount');
                $credits = $customer->ledgerEntries->where('type', 'credit')->sum('amount');
                $calculatedBalance = $debits - $credits;

                return [
                    'customer' => $customer,
                    'debit' => $debits,
                    'credit' => $credits,
                    'balance' => $calculatedBalance,
                ];
            });

        // Filter customers by balance status if requested
        if ($request->filled('balance_status')) {
            $customers = $customers->filter(function ($data) use ($request) {
                if ($request->balance_status === 'debit')
                    return $data['balance'] > 0;
                if ($request->balance_status === 'credit')
                    return $data['balance'] < 0;
                if ($request->balance_status === 'balanced')
                    return $data['balance'] == 0;
                return true;
            });
        }

        return view('accounting.reconciliation.index', compact('errors', 'consistent', 'customers'));
    }

    /**
     * Fix inconsistent invoice statuses.
     */
    public function fix(Request $request)
    {
        $invoices = Invoice::all();
        $count = 0;

        foreach ($invoices as $invoice) {
            // updateStatus() logic in Invoice model handles:
            // - if paid >= grand_total -> status = paid
            // - if paid < grand_total -> status = partial/overdue/sent

            // We'll trust the model's logic to fix the status based on amounts
            $originalStatus = $invoice->status;
            $invoice->updateStatus();

            if ($invoice->isDirty('status') || $invoice->wasChanged('status')) {
                $count++;
            }
        }

        return redirect()->route('accounting.reconciliation.index')
            ->with('success', "{$count} fatura durumu güncellendi.");
    }
}

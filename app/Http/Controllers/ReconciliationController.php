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
            $status = $invoice->status;

            // Rule 1: Overpayment check (with small epsilon for float precision)
            if ($paidAmount > $grandTotal + 0.01)
                return true;

            // Rule 2: Full payment but status not 'paid'
            if ($paidAmount >= $grandTotal && $grandTotal > 0 && $status !== 'paid')
                return true;

            // Rule 3: Partial payment but status not 'partial'
            if ($paidAmount > 0 && $paidAmount < $grandTotal && $status !== 'partial')
                return true;

            // Rule 4: Zero payment but status is 'paid' or 'partial'
            if ($paidAmount <= 0 && ($status === 'paid' || $status === 'partial'))
                return true;

            // Rule 5: Status is 'paid' but not actually fully paid
            if ($status === 'paid' && $paidAmount < $grandTotal)
                return true;

            return false;
        });

        $consistent = $invoices->diff($errors);

        // 2. Customer Balances (Refactored for performance)
        $customerQuery = Customer::query();

        if ($request->filled('search')) {
            $customerQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $allCustomers = $customerQuery->get();
        $customerIds = $allCustomers->pluck('id')->toArray();

        $financeService = new \App\Services\FinanceService();
        $balances = $financeService->getCustomersDetailedBalances($customerIds);

        $customers = $allCustomers->map(function ($customer) use ($balances) {
            $customerBalances = $balances->get($customer->id, collect());
            $tryBalance = $customerBalances->get('TRY', ['debit' => 0, 'credit' => 0, 'balance' => 0]);

            return [
                'customer' => $customer,
                'balances' => $customerBalances, // All currencies with details
                'debit' => $tryBalance['debit'],
                'credit' => $tryBalance['credit'],
                'balance' => $tryBalance['balance'],
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

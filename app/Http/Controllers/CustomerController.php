<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Balance Status Filter (ANY Currency)
        if ($request->filled('balance_status')) {
            if ($request->balance_status === 'debit') {
                // Customers who have MORE debits than credits in at least one currency
                $query->whereHas('ledgerEntries', function ($q) {
                    $q->select('currency')
                        ->groupBy('currency')
                        ->havingRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END) > 0');
                });
            } elseif ($request->balance_status === 'credit') {
                // Customers who have MORE credits than debits in at least one currency
                $query->whereHas('ledgerEntries', function ($q) {
                    $q->select('currency')
                        ->groupBy('currency')
                        ->havingRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END) < 0');
                });
            } elseif ($request->balance_status === 'balanced') {
                // Customers with no active balance (either no entries or perfect 0)
                $query->whereDoesntHave('ledgerEntries', function ($q) {
                    $q->select('currency')
                        ->groupBy('currency')
                        ->havingRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END) != 0');
                });
            }
        }

        // Get customers with pagination
        $customers = $query->withCount('services')->latest()->paginate(15)->withQueryString();

        // Financial KPIs using FinanceService
        $financeService = new \App\Services\FinanceService();
        $globalSummary = $financeService->getGlobalSummary();

        // For now, we show TRY totals if available, otherwise 0
        // Future improvement: Show multi-currency cards or a selector
        $trySummary = $globalSummary['TRY'] ?? ['receivable' => 0, 'payable' => 0];
        $totalReceivable = $trySummary['receivable'];
        $totalPayable = $trySummary['payable'];

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::active()->count();

        return view('customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'totalReceivable',
            'totalPayable',
            'globalSummary' // Pass the whole summary for potentially showing USD/EUR cards too
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla silindi.');
    }

    /**
     * TomSelect AJAX search endpoint.
     * Returns JSON: [{id, text, name, email, tax_number}]
     */
    public function apiSearch(Request $request)
    {
        $q = $request->input('q', '');

        $customers = Customer::query()
            ->when($q, fn($query) => $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('tax_or_identity_number', 'like', "%{$q}%");
            }))
            ->orderBy('name')
            ->limit(30)
            ->get(['id', 'name', 'email', 'tax_or_identity_number']);

        return response()->json(
            $customers->map(fn($c) => [
                'id' => $c->id,
                'text' => $c->name,
                'name' => $c->name,
                'email' => $c->email ?? '',
                'tax_number' => $c->tax_or_identity_number ?? '',
            ])
        );
    }
}

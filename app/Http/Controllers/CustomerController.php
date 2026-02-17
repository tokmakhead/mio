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

        // Balance Status Filter (Subquery for consistency)
        if ($request->filled('balance_status')) {
            $defaultCurrency = 'TRY'; // Could be dynamic from settings

            $query->addSelect([
                'balance' => \App\Models\LedgerEntry::selectRaw('SUM(CASE WHEN type = \'debit\' THEN amount ELSE -amount END)')
                    ->whereColumn('customer_id', 'customers.id')
                    ->where('currency', $defaultCurrency)
            ]);

            if ($request->balance_status === 'debit') {
                $query->having('balance', '>', 0);
            } elseif ($request->balance_status === 'credit') {
                $query->having('balance', '<', 0);
            } elseif ($request->balance_status === 'balanced') {
                $query->having('balance', '=', 0)
                    ->orHavingRaw('balance IS NULL');
            }
        }

        // Get customers with pagination
        $customers = $query->withCount('services')->latest()->paginate(15)->withQueryString();

        // KPIs
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::active()->count();

        // Actual calculations for KPIs (Default Currency: TRY)
        $defaultCurrency = 'TRY';
        $customerBalances = Customer::all()->map(fn($c) => $c->balances[$defaultCurrency] ?? 0);

        $totalReceivable = $customerBalances->filter(fn($b) => $b > 0)->sum();
        $totalPayable = abs($customerBalances->filter(fn($b) => $b < 0)->sum());

        return view('customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'totalReceivable',
            'totalPayable'
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
}

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

        // Balance Status Filter (Subquery for efficiency)
        if ($request->filled('balance_status')) {
            $query->whereHas('ledgerEntries', function ($q) use ($request) {
                // This is a bit tricky with whereHas if we need the SUM
                // Better to use whereRaw or a subquery if performance matters
            });

            // Simplified approach: Filter in collection or use a more complex query
            // Let's use a subquery to calculate balance
            $query->addSelect([
                'balance' => \App\Models\LedgerEntry::selectRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END)')
                    ->whereColumn('customer_id', 'customers.id')
            ]);

            if ($request->balance_status === 'debit') {
                $query->having('balance', '>', 0);
            } elseif ($request->balance_status === 'credit') {
                $query->having('balance', '<', 0);
            } elseif ($request->balance_status === 'balanced') {
                $query->having('balance', '=', 0);
            }
        }

        // Get customers with pagination
        $customers = $query->withCount('services')->latest()->paginate(20)->withQueryString();

        // KPIs
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::active()->count();

        // Actual calculations for KPIs
        $totalReceivable = \App\Models\LedgerEntry::where('type', 'debit')
            ->whereHas('customer', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->sum('amount') - \App\Models\LedgerEntry::where('type', 'credit')
                ->whereHas('customer', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->sum('amount');

        // This is a global balance. Let's refine it to separate positive and negative totals if needed
        // For now, let's keep it simple or follow the KPI card titles: "Toplam Alacak" / "Toplam Borç"
        $customerBalances = Customer::all()->map(fn($c) => $c->balance);
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

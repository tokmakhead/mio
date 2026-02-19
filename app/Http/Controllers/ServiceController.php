<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Customer;
use App\Models\Provider;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::with(['customer', 'provider']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('identifier_code', 'like', "%{$search}%");
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

        // Expiring soon filter
        if ($request->filled('expiring_soon')) {
            $query->expiringSoon(30);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $services = $query->paginate(15);

        // KPIs
        $totalServices = Service::count();
        $domainCount = Service::where('type', 'domain')->count();
        $hostingCount = Service::where('type', 'hosting')->count();
        // MRR Calculation Grouped by Currency
        $mrrByCurrency = Service::active()->get()
            ->groupBy('currency')
            ->map(function ($services) {
                return $services->sum('mrr');
            });

        // For backward compatibility with view, we might need a default, 
        // but best is to pass the collection.
        // The view uses $mrr scalar. I will pass $mrrByCurrency to view 
        // and also keeping $mrr as TRY for fallback or sum? No, sum is wrong.
        // Let's pass $mrrByCurrency.


        return view('services.index', compact(
            'services',
            'totalServices',
            'domainCount',
            'hostingCount',
            'hostingCount',
            'mrrByCurrency'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $providers = Provider::orderBy('name')->get();

        return view('services.create', compact('customers', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();

        if (empty($data['identifier_code'])) {
            $prefix = match ($data['type']) {
                'hosting' => 'HST',
                'domain' => 'DOM',
                'ssl' => 'SSL',
                'email' => 'EML',
                default => 'SRV',
            };
            $data['identifier_code'] = $prefix . '-' . strtoupper(\Illuminate\Support\Str::random(8));
        }

        Service::create($data);

        return redirect()->route('services.index')
            ->with('success', 'Hizmet başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['customer', 'provider']);

        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $customers = Customer::orderBy('name')->get();
        $providers = Provider::orderBy('name')->get();

        return view('services.edit', compact('service', 'customers', 'providers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Hizmet başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Hizmet başarıyla silindi.');
    }
}

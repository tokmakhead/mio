<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Provider::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $providers = $query->withCount('services')->latest()->paginate(15);

        // KPIs
        $totalProviders = Provider::count();
        $uniqueTypes = Provider::all()->pluck('types')->flatten()->unique()->count();
        $withWebsite = Provider::whereNotNull('website')->count();

        // Total Costs (Payables) to providers based on active services
        try {
            $totalCosts = \App\Models\Service::active()
                ->select('currency', \DB::raw('SUM(buying_price) as total'))
                ->groupBy('currency')
                ->pluck('total', 'currency');
        } catch (\Exception $e) {
            $totalCosts = collect();
        }

        $activeServices = \App\Models\Service::where('status', 'active')->count();

        return view('providers.index', compact(
            'providers',
            'totalProviders',
            'uniqueTypes',
            'withWebsite',
            'totalCosts',
            'activeServices'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProviderRequest $request)
    {
        $data = $request->validated();

        // Handle custom type
        if (in_array('other', $data['types']) && !empty($request->custom_type)) {
            $data['types'] = array_diff($data['types'], ['other']);
            $data['types'][] = $request->custom_type;
        }
        $data['types'] = array_values($data['types']);

        Provider::create($data);

        return redirect()->route('providers.index')
            ->with('success', 'Sağlayıcı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        return view('providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProviderRequest $request, Provider $provider)
    {
        $data = $request->validated();

        // Handle custom type
        if (in_array('other', $data['types']) && !empty($request->custom_type)) {
            $data['types'] = array_diff($data['types'], ['other']);
            $data['types'][] = $request->custom_type;
        }
        $data['types'] = array_values($data['types']);

        $provider->update($data);

        return redirect()->route('providers.index')
            ->with('success', 'Sağlayıcı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();

        return redirect()->route('providers.index')
            ->with('success', 'Sağlayıcı başarıyla silindi.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = \App\Models\Customer::count();
        $activeServicesCount = \App\Models\Service::active()->count();
        $mrr = \App\Models\Service::active()->get()->sum('mrr');
        $overdueInvoices = 0; // Placeholder

        $expiringServices = \App\Models\Service::with('customer')->active()->expiringSoon(60)->orderBy('end_date')->take(5)->get();

        return view('dashboard', compact(
            'totalCustomers',
            'activeServicesCount',
            'mrr',
            'overdueInvoices',
            'expiringServices'
        ));
    }
}

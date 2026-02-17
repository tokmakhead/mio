<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(\App\Services\AnnouncementService $announcementService)
    {
        $announcements = $announcementService->fetch();

        $now = now();
        $thisMonth = $now->month;
        $thisYear = $now->year;

        // Default currency for main KPIs
        $siteSettings = \App\Models\SystemSetting::first();
        $defaultCurrency = $siteSettings->default_currency ?? 'TRY';

        // Global counts (Currency-independent)
        $globalMetricsResult = \DB::select("
            SELECT 
                (SELECT COUNT(*) FROM customers) as total_customers,
                (SELECT COUNT(*) FROM services WHERE status = 'active') as active_services,
                (SELECT COUNT(*) FROM invoices WHERE status = 'overdue') as overdue_invoices,
                (SELECT COALESCE(AVG(grand_total), 0) FROM invoices WHERE currency = ?) as avg_invoice
        ", [$defaultCurrency])[0];

        // Currency-aware metrics (Breakdown)
        $revenueMetrics = \DB::table('payments')
            ->select('currency', \DB::raw('SUM(amount) as total'))
            ->whereMonth('paid_at', $thisMonth)
            ->whereYear('paid_at', $thisYear)
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $invoiceMetrics = \DB::table('invoices')
            ->select(
                'currency',
                \DB::raw('SUM(grand_total) as billed'),
                \DB::raw('SUM(paid_amount) as collected')
            )
            ->groupBy('currency')
            ->get()
            ->keyBy('currency');

        $mrrMetrics = \App\Models\Service::active()
            ->select('currency', \DB::raw('SUM(price) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $totalCustomers = $globalMetricsResult->total_customers;
        $activeServicesCount = $globalMetricsResult->active_services;
        $overdueInvoices = $globalMetricsResult->overdue_invoices;
        $avgInvoice = $globalMetricsResult->avg_invoice;

        // KPI Display values based on default currency
        $thisMonthRevenue = $revenueMetrics[$defaultCurrency] ?? 0;
        $mrr = $mrrMetrics[$defaultCurrency] ?? 0;

        $billedTotal = $invoiceMetrics[$defaultCurrency]->billed ?? 0;
        $collectedTotal = $invoiceMetrics[$defaultCurrency]->collected ?? 0;
        $pendingTotal = $billedTotal - $collectedTotal;

        // Revenue Trend Chart (Last 6 months) for Default Currency
        $revenueTrend = $this->getRevenueTrend($defaultCurrency);

        // MRR Distribution (by type) - Optimized
        $mrrDistribution = \App\Models\Service::active()
            ->select('type', \DB::raw('SUM(price) as total_price'))
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => \App\Models\Service::getTypeLabel($item->type),
                    'value' => $item->total_price,
                ];
            });

        // Upcoming Expiries - Optimized with eager loading
        $expiringServices = \App\Models\Service::with('customer:id,name')
            ->active()
            ->expiringSoon(90)
            ->orderBy('end_date')
            ->limit(10)
            ->get();

        // Recent Activities - Optimized with eager loading
        $recentActivities = \App\Models\ActivityLog::with('actor:id,name', 'subject')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'activeServicesCount',
            'thisMonthRevenue',
            'overdueInvoices',
            'mrr',
            'revenueTrend',
            'mrrDistribution',
            'billedTotal',
            'collectedTotal',
            'pendingTotal',
            'avgInvoice',
            'expiringServices',
            'recentActivities',
            'announcements',
            'revenueMetrics',
            'invoiceMetrics',
            'mrrMetrics'
        ));
    }

    private function getRevenueTrend($currency = 'TRY')
    {
        // MySQL-compatible version
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i)->startOfMonth();

            $billed = \App\Models\Invoice::whereYear('issue_date', $date->year)
                ->whereMonth('issue_date', $date->month)
                ->where('currency', $currency)
                ->sum('grand_total');

            $collected = \App\Models\Payment::whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->where('currency', $currency)
                ->sum('amount');

            $months[] = [
                'month' => $date->format('M'),
                'billed' => $billed ?? 0,
                'collected' => $collected ?? 0,
            ];
        }

        return $months;
    }
}

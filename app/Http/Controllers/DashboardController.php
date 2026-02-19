<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(\App\Services\AnnouncementService $announcementService)
    {
        $announcements = $announcementService->fetch(); // Remote/External, keep instant or cache separately inside service

        // Cache Key based on default currency to handle settings changes
        $siteSettings = \App\Models\SystemSetting::first();
        $defaultCurrency = $siteSettings->default_currency ?? 'TRY';
        $cacheKey = 'dashboard_stats_' . $defaultCurrency;

        // Cache for 30 minutes. Observers will clear this on new data.
        $dashboardData = \Illuminate\Support\Facades\Cache::remember($cacheKey, 1800, function () use ($defaultCurrency) {
            $financeService = new \App\Services\FinanceService();
            $now = now();
            $thisMonth = $now->month;
            $thisYear = $now->year;

            // Global counts (Currency-independent)
            $globalCounts = \DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM customers) as total_customers,
                    (SELECT COUNT(*) FROM services WHERE status = 'active') as active_services,
                    (SELECT COUNT(*) FROM invoices WHERE status = 'overdue') as overdue_invoices
            ")[0];

            // Optimized Financial Metrics via FinanceService
            $currencySummary = $financeService->getGlobalSummary();

            // Specific metrics for MRR and Revenue (Already grouped by currency)
            $revenueMetrics = \DB::table('payments')
                ->select('currency', \DB::raw('SUM(amount) as total'))
                ->whereMonth('paid_at', $thisMonth)
                ->whereYear('paid_at', $thisYear)
                ->groupBy('currency')
                ->pluck('total', 'currency');

            $mrrMetrics = \App\Models\Service::active()
                ->select('currency', \DB::raw('SUM(price) as total'))
                ->groupBy('currency')
                ->pluck('total', 'currency');

            // Revenue Trend Chart
            $revenueTrend = $this->getRevenueTrend($defaultCurrency);

            // Summary for primary KPI display
            $primarySummary = $currencySummary[$defaultCurrency] ?? ['receivable' => 0, 'payable' => 0, 'net' => 0];
            $thisMonthRevenue = $revenueMetrics[$defaultCurrency] ?? 0;
            $mrr = $mrrMetrics[$defaultCurrency] ?? 0;

            // Average invoice
            $avgInvoice = \App\Models\Invoice::where('currency', $defaultCurrency)->avg('grand_total') ?? 0;

            // MRR Distribution
            $mrrDistribution = \App\Models\Service::active()
                ->select('type', \DB::raw('SUM(price) as total_price'))
                ->where('currency', $defaultCurrency)
                ->groupBy('type')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => \App\Models\Service::getTypeLabel($item->type),
                        'value' => (float) $item->total_price,
                    ];
                });

            // Upcoming Expiries
            $expiringServices = \App\Models\Service::with('customer:id,name')
                ->active()
                ->expiringSoon(90)
                ->orderBy('end_date')
                ->limit(10)
                ->get();

            // Recent Activities
            $recentActivities = \App\Models\ActivityLog::with('actor:id,name', 'subject')
                ->latest()
                ->limit(10)
                ->get();

            // Top 5 Customers (By Revenue in Default Currency)
            $topCustomers = \App\Models\Payment::with('customer:id,name,email,company_name')
                ->select('customer_id', \DB::raw('SUM(amount) as total_paid'))
                ->where('currency', $defaultCurrency)
                ->groupBy('customer_id')
                ->orderByDesc('total_paid')
                ->limit(5)
                ->get();

            // Overdue Total (Vadesi Geçmiş Alacaklar)
            $overdueTotal = \App\Models\Invoice::where('currency', $defaultCurrency)
                ->where('status', '!=', 'paid')
                ->where('status', '!=', 'cancelled')
                ->where('due_date', '<', $now->startOfDay())
                ->sum(\DB::raw('grand_total - paid_amount'));

            return [
                'totalCustomers' => $globalCounts->total_customers,
                'activeServicesCount' => $globalCounts->active_services,
                'overdueInvoices' => $globalCounts->overdue_invoices,
                'thisMonthRevenue' => $thisMonthRevenue,
                'mrr' => $mrr,
                'revenueTrend' => $revenueTrend,
                'mrrDistribution' => $mrrDistribution,
                'billedTotal' => $primarySummary['receivable'] + $primarySummary['payable'],
                'collectedTotal' => $primarySummary['receivable'],
                'pendingTotal' => $primarySummary['payable'], // Keep for compatibility if needed, but UI will use overdueTotal or rename this variable
                'overdueTotal' => $overdueTotal,
                'avgInvoice' => $avgInvoice,
                'expiringServices' => $expiringServices,
                'recentActivities' => $recentActivities,
                'topCustomers' => $topCustomers,
                'currencySummary' => $currencySummary,
                'revenueMetrics' => $revenueMetrics,
                'mrrMetrics' => $mrrMetrics,
            ];
        });

        // Add non-cached data
        $dashboardData['announcements'] = $announcements;
        $dashboardData['defaultCurrency'] = $defaultCurrency;

        return view('dashboard', $dashboardData);
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

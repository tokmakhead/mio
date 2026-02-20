<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Payment;
use App\Models\SystemSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(\App\Services\AnnouncementService $announcementService)
    {
        try {
            $announcements = $announcementService->fetch();

            // Cache Key based on default currency
            $siteSettings = \App\Models\SystemSetting::first();
            $defaultCurrency = $siteSettings->default_currency ?? 'TRY';
            $cacheKey = 'dashboard_stats_v3_' . $defaultCurrency; // Updated version to force cache refresh after type change

            $dashboardData = \Illuminate\Support\Facades\Cache::remember($cacheKey, 1800, function () use ($defaultCurrency) {
                $financeService = new \App\Services\FinanceService();
                $now = now();

                // Robust counts
                $data = [
                    'totalCustomers' => Customer::count(),
                    'activeServicesCount' => Service::active()->count(),
                    'overdueInvoices' => Invoice::where('status', 'overdue')->count(),
                ];

                // Optimized Financial Metrics
                $currencySummary = $financeService->getGlobalSummary();

                // Revenue Trend Chart
                $data['revenueTrend'] = $this->getRevenueTrend($defaultCurrency);

                // Specific metrics
                $primarySummary = $currencySummary[$defaultCurrency] ?? ['receivable' => 0, 'payable' => 0, 'net' => 0];

                $data['thisMonthRevenue'] = Payment::where('currency', $defaultCurrency)
                    ->whereMonth('paid_at', $now->month)
                    ->whereYear('paid_at', $now->year)
                    ->sum('amount');

                $data['mrr'] = Service::active()
                    ->where('currency', $defaultCurrency)
                    ->isRecurring()
                    ->get()
                    ->sum('mrr');

                // Average invoice
                $data['avgInvoice'] = Invoice::where('currency', $defaultCurrency)->avg('grand_total') ?? 0;

                // MRR Distribution (Aggregate all currencies or group by type regardless of currency for the chart)
                $mrrDistribution = Service::active()
                    ->select('type', DB::raw('SUM(price) as total_price'))
                    // ->where('currency', $defaultCurrency) // REMOVED FILTER to show global distribution
                    ->groupBy('type')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => Service::getTypeLabel($item->type),
                            'value' => (float) $item->total_price,
                        ];
                    });

                // Overdue Total
                $overdueTotal = Invoice::where('currency', $defaultCurrency)
                    ->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled')
                    ->where('due_date', '<', $now->startOfDay())
                    ->sum(DB::raw('grand_total - paid_amount'));

                // Merge all
                return array_merge($data, [
                    'mrrDistribution' => $mrrDistribution,
                    'billedTotal' => $primarySummary['receivable'] + $primarySummary['payable'],
                    'collectedTotal' => $primarySummary['receivable'],
                    'pendingTotal' => $primarySummary['payable'],
                    'overdueTotal' => $overdueTotal,
                    'expiringServices' => Service::with('customer:id,name')->active()->expiringSoon(90)->orderBy('end_date')->limit(10)->get(),
                    'recentActivities' => ActivityLog::with('actor:id,name', 'subject')->latest()->limit(10)->get(),
                    'topCustomers' => Payment::with('customer:id,name,email')
                        ->select('customer_id', DB::raw('SUM(amount) as total_paid'))
                        ->where('currency', $defaultCurrency)
                        ->groupBy('customer_id')
                        ->orderByDesc('total_paid')
                        ->limit(5)
                        ->get(),
                    'currencySummary' => $currencySummary,
                    'revenueMetrics' => Payment::select('currency', DB::raw('SUM(amount) as total'))->whereMonth('paid_at', $now->month)->whereYear('paid_at', $now->year)->groupBy('currency')->pluck('total', 'currency'),
                    'mrrMetrics' => Service::active()->select('currency', DB::raw('SUM(price) as total'))->groupBy('currency')->pluck('total', 'currency'),
                ]);
            });

            $dashboardData['announcements'] = $announcements;
            $dashboardData['defaultCurrency'] = $defaultCurrency;

            // Type cast to Collection if they are arrays (happens during cache serialization)
            $dashboardData['currencySummary'] = collect($dashboardData['currencySummary']);
            $dashboardData['revenueMetrics'] = collect($dashboardData['revenueMetrics']);
            $dashboardData['mrrMetrics'] = collect($dashboardData['mrrMetrics']);

            return view('dashboard', $dashboardData);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            \Log::error('Dashboard error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->view('errors.500', ['exception' => $e], 500);
        }
    }

    private function getRevenueTrend($currency = 'TRY')
    {
        // MySQL-compatible version
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i)->startOfMonth();

            $billed = Invoice::whereYear('issue_date', $date->year)
                ->whereMonth('issue_date', $date->month)
                ->where('currency', $currency)
                ->sum('grand_total');

            $collected = Payment::whereYear('paid_at', $date->year)
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

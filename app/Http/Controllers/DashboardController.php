<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(\App\Services\AnnouncementService $announcementService)
    {
        try {
            $announcements = $announcementService->fetch();

            // Cache Key based on default currency
            $siteSettings = \App\Models\SystemSetting::first();
            $defaultCurrency = $siteSettings->default_currency ?? 'TRY';
            $cacheKey = 'dashboard_stats_v2_' . $defaultCurrency; // Versioned cache key

            $dashboardData = \Illuminate\Support\Facades\Cache::remember($cacheKey, 1800, function () use ($defaultCurrency) {
                $financeService = new \App\Services\FinanceService();
                $now = now();

                // Robust counts
                $data = [
                    'totalCustomers' => \App\Models\Customer::count(),
                    'activeServicesCount' => \App\Models\Service::active()->count(),
                    'overdueInvoices' => \App\Models\Invoice::where('status', 'overdue')->count(),
                ];

                // Optimized Financial Metrics
                $currencySummary = $financeService->getGlobalSummary();

                // Revenue Trend Chart
                $data['revenueTrend'] = $this->getRevenueTrend($defaultCurrency);

                // Specific metrics
                $primarySummary = $currencySummary[$defaultCurrency] ?? ['receivable' => 0, 'payable' => 0, 'net' => 0];

                $data['thisMonthRevenue'] = \App\Models\Payment::where('currency', $defaultCurrency)
                    ->whereMonth('paid_at', $now->month)
                    ->whereYear('paid_at', $now->year)
                    ->sum('amount');

                $data['mrr'] = \App\Models\Service::active()
                    ->where('currency', $defaultCurrency)
                    ->isRecurring()
                    ->get()
                    ->sum('mrr');

                // Average invoice
                $data['avgInvoice'] = \App\Models\Invoice::where('currency', $defaultCurrency)->avg('grand_total') ?? 0;

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

                // Overdue Total
                $overdueTotal = \App\Models\Invoice::where('currency', $defaultCurrency)
                    ->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled')
                    ->where('due_date', '<', $now->startOfDay())
                    ->sum(\DB::raw('grand_total - paid_amount'));

                // Merge all
                return array_merge($data, [
                    'mrrDistribution' => $mrrDistribution,
                    'billedTotal' => $primarySummary['receivable'] + $primarySummary['payable'],
                    'collectedTotal' => $primarySummary['receivable'],
                    'pendingTotal' => $primarySummary['payable'],
                    'overdueTotal' => $overdueTotal,
                    'expiringServices' => \App\Models\Service::with('customer:id,name')->active()->expiringSoon(90)->orderBy('end_date')->limit(10)->get(),
                    'recentActivities' => \App\Models\ActivityLog::with('actor:id,name', 'subject')->latest()->limit(10)->get(),
                    'topCustomers' => \App\Models\Payment::with('customer:id,name,email')
                        ->select('customer_id', \DB::raw('SUM(amount) as total_paid'))
                        ->where('currency', $defaultCurrency)
                        ->groupBy('customer_id')
                        ->orderByDesc('total_paid')
                        ->limit(5)
                        ->get(),
                    'currencySummary' => $currencySummary,
                    'revenueMetrics' => \App\Models\Payment::select('currency', \DB::raw('SUM(amount) as total'))->whereMonth('paid_at', $now->month)->whereYear('paid_at', $now->year)->groupBy('currency')->pluck('total', 'currency'),
                    'mrrMetrics' => \App\Models\Service::active()->select('currency', \DB::raw('SUM(price) as total'))->groupBy('currency')->pluck('total', 'currency'),
                ]);
            });

            $dashboardData['announcements'] = $announcements;
            $dashboardData['defaultCurrency'] = $defaultCurrency;

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

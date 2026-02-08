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

        // 4 KPI
        $totalCustomers = \App\Models\Customer::count();
        $activeServicesCount = \App\Models\Service::active()->count();
        $thisMonthRevenue = \App\Models\Payment::whereMonth('paid_at', $thisMonth)
            ->whereYear('paid_at', $thisYear)
            ->sum('amount');
        $overdueInvoices = \App\Models\Invoice::where('status', 'overdue')->count();

        // MRR
        $mrr = \App\Models\Service::active()->get()->sum('mrr');

        // Revenue Trend Chart (Last 6 months example)
        $revenueTrend = $this->getRevenueTrend();

        // MRR Distribution (by type)
        $mrrDistribution = \App\Models\Service::active()
            ->select('type', \DB::raw('SUM(price) as total_price')) // Simplification for demo
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => \App\Models\Service::getTypeLabel($item->type),
                    'value' => $item->total_price,
                ];
            });

        // Financial Summary
        $villedTotal = \App\Models\Invoice::sum('grand_total');
        $collectedTotal = \App\Models\Invoice::sum('paid_amount');
        $pendingTotal = $villedTotal - $collectedTotal;
        $avgInvoice = \App\Models\Invoice::avg('grand_total') ?? 0;

        // Upcoming Expiries
        $expiringServices = \App\Models\Service::with('customer')
            ->active()
            ->expiringSoon(90)
            ->orderBy('end_date')
            ->take(10)
            ->get();

        // Recent Activities
        $recentActivities = \App\Models\ActivityLog::with('actor', 'subject')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'activeServicesCount',
            'thisMonthRevenue',
            'overdueInvoices',
            'mrr',
            'revenueTrend',
            'mrrDistribution',
            'villedTotal',
            'collectedTotal',
            'pendingTotal',
            'avgInvoice',
            'expiringServices',
            'recentActivities',
            'announcements'
        ));
    }

    private function getRevenueTrend()
    {
        // Simple trend for last 6 months
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $billed = \App\Models\Invoice::whereMonth('issue_date', $date->month)
                ->whereYear('issue_date', $date->year)
                ->sum('grand_total');

            $collected = \App\Models\Payment::whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');

            $data[] = [
                'month' => $date->format('M'),
                'billed' => $billed,
                'collected' => $collected
            ];
        }

        return $data;
    }
}

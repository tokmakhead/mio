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

        // Single optimized query for all KPIs and metrics
        $metrics = \DB::select("
            SELECT 
                (SELECT COUNT(*) FROM customers) as total_customers,
                (SELECT COUNT(*) FROM services WHERE status = 'active') as active_services,
                (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE EXTRACT(MONTH FROM paid_at) = ? AND EXTRACT(YEAR FROM paid_at) = ?) as this_month_revenue,
                (SELECT COUNT(*) FROM invoices WHERE status = 'overdue') as overdue_invoices,
                (SELECT COALESCE(SUM(price), 0) FROM services WHERE status = 'active') as mrr,
                (SELECT COALESCE(SUM(grand_total), 0) FROM invoices) as billed_total,
                (SELECT COALESCE(SUM(paid_amount), 0) FROM invoices) as collected_total,
                (SELECT COALESCE(AVG(grand_total), 0) FROM invoices) as avg_invoice
        ", [$thisMonth, $thisYear])[0];

        $totalCustomers = $metrics->total_customers;
        $activeServicesCount = $metrics->active_services;
        $thisMonthRevenue = $metrics->this_month_revenue;
        $overdueInvoices = $metrics->overdue_invoices;
        $mrr = $metrics->mrr;
        $villedTotal = $metrics->billed_total;
        $collectedTotal = $metrics->collected_total;
        $pendingTotal = $villedTotal - $collectedTotal;
        $avgInvoice = $metrics->avg_invoice;

        // Revenue Trend Chart (Last 6 months) - Optimized
        $revenueTrend = $this->getRevenueTrend();

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
        // MySQL-compatible version
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i)->startOfMonth();

            $billed = \App\Models\Invoice::whereYear('issue_date', $date->year)
                ->whereMonth('issue_date', $date->month)
                ->sum('grand_total');

            $collected = \App\Models\Payment::whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
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

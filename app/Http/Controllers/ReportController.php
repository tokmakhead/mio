<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_services' => Service::active()->count(),
            'total_revenue' => Payment::sum('amount'),
            'total_providers' => Provider::count(),
        ];

        return view('reports.index', compact('stats'));
    }

    public function revenue(Request $request)
    {
        $period = $request->input('period', 6); // Default 6 months
        $startDate = now()->subMonths($period)->startOfMonth();
        $endDate = now()->endOfMonth();

        // Monthly Data
        $monthlyData = $this->getMonthlyData($startDate, $endDate);

        // Chart Data
        $labels = $monthlyData->pluck('month');
        $invoicedData = $monthlyData->pluck('invoiced');
        $collectedData = $monthlyData->pluck('collected');

        // Totals for the period
        $totalInvoiced = $monthlyData->sum('invoiced');
        $totalCollected = $monthlyData->sum('collected');
        $totalPending = $monthlyData->sum('pending');
        $averageInvoice = $monthlyData->sum('invoice_count') > 0 ? $totalInvoiced / $monthlyData->sum('invoice_count') : 0;

        return view('reports.revenue', compact(
            'monthlyData',
            'labels',
            'invoicedData',
            'collectedData',
            'totalInvoiced',
            'totalCollected',
            'totalPending',
            'averageInvoice',
            'period'
        ));
    }

    public function revenueCsv(Request $request)
    {
        $period = $request->input('period', 6);
        $startDate = now()->subMonths($period)->startOfMonth();
        $endDate = now()->endOfMonth();

        $monthlyData = $this->getMonthlyData($startDate, $endDate);

        $filename = "gelir-analizi-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($handle, $monthlyData) {
            fputcsv($handle, ['Ay', 'Kesilen Fatura (TL)', 'Tahsilat (TL)', 'Bekleyen (TL)', 'Fatura Adeti']);

            foreach ($monthlyData as $row) {
                fputcsv($handle, [
                    $row['month'],
                    $row['invoiced'],
                    $row['collected'],
                    $row['pending'],
                    $row['invoice_count']
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function revenuePdf(Request $request)
    {
        $period = $request->input('period', 6);
        $startDate = now()->subMonths($period)->startOfMonth();
        $endDate = now()->endOfMonth();

        $monthlyData = $this->getMonthlyData($startDate, $endDate);

        $totalInvoiced = $monthlyData->sum('invoiced');
        $totalCollected = $monthlyData->sum('collected');
        $totalPending = $monthlyData->sum('pending');
        $averageInvoice = $monthlyData->sum('invoice_count') > 0 ? $totalInvoiced / $monthlyData->sum('invoice_count') : 0;

        $pdf = Pdf::loadView('reports.revenue_pdf', compact(
            'monthlyData',
            'totalInvoiced',
            'totalCollected',
            'totalPending',
            'averageInvoice',
            'period'
        ));

        return $pdf->download('gelir-analizi.pdf');
    }

    private function getMonthlyData($startDate, $endDate)
    {
        // Generate month range
        $months = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $months[$current->format('Y-m')] = [
                'month' => $current->translatedFormat('F Y'),
                'invoiced' => 0,
                'collected' => 0,
                'pending' => 0,
                'invoice_count' => 0,
            ];
            $current->addMonth();
        }

        // Fetch Invoiced Data
        $invoices = Invoice::whereBetween('issue_date', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(issue_date, '%Y-%m') as month, SUM(grand_total) as total, COUNT(*) as count, SUM(grand_total - paid_amount) as pending")
            ->groupBy('month')
            ->get();

        foreach ($invoices as $inv) {
            if (isset($months[$inv->month])) {
                $months[$inv->month]['invoiced'] = $inv->total;
                $months[$inv->month]['invoice_count'] = $inv->count;
                // Pending based on issue date logic (simplification)
                // Actually pending should be grand_total - paid_amount for invoices issued in that month
                $months[$inv->month]['pending'] = $inv->pending;
            }
        }

        // Fetch Collected Data (Payments)
        $payments = Payment::whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->get();

        foreach ($payments as $pym) {
            if (isset($months[$pym->month])) {
                $months[$pym->month]['collected'] = $pym->total;
            }
        }

        return collect($months)->values();
    }
}

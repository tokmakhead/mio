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
        $financeService = new \App\Services\FinanceService();
        $globalSummary = $financeService->getGlobalSummary();

        // Use default currency for the main card, but prepare for multi-currency list
        $defaultCurrency = \App\Models\SystemSetting::first()->default_currency ?? 'TRY';
        $totalRevenue = Payment::where('currency', $defaultCurrency)->sum('amount');

        $stats = [
            'total_customers' => Customer::count(),
            'active_services' => Service::active()->count(),
            'total_revenue' => $totalRevenue,
            'total_providers' => Provider::count(),
            'currency_summary' => $globalSummary,
            'default_currency' => $defaultCurrency
        ];

        return view('reports.index', compact('stats'));
    }

    public function revenue(Request $request)
    {
        $period = $request->input('period', 6);
        $currency = $request->input('currency', \App\Models\SystemSetting::first()->default_currency ?? 'TRY');

        $startDate = now()->subMonths($period)->startOfMonth();
        $endDate = now()->endOfMonth();

        // Monthly Data (Filtered by currency)
        $monthlyData = $this->getMonthlyData($startDate, $endDate, $currency);

        // Chart Data
        $labels = $monthlyData->pluck('month');
        $invoicedData = $monthlyData->pluck('invoiced');
        $collectedData = $monthlyData->pluck('collected');

        // Totals for the period
        $totalInvoiced = $monthlyData->sum('invoiced');
        $totalCollected = $monthlyData->sum('collected');
        $totalPending = $monthlyData->sum('pending');
        $averageInvoice = $monthlyData->sum('invoice_count') > 0 ? $totalInvoiced / $monthlyData->sum('invoice_count') : 0;

        // Available currencies for filter dropdown
        $availableCurrencies = DB::table('invoices')->distinct()->pluck('currency');

        return view('reports.revenue', compact(
            'monthlyData',
            'labels',
            'invoicedData',
            'collectedData',
            'totalInvoiced',
            'totalCollected',
            'totalPending',
            'averageInvoice',
            'period',
            'currency',
            'availableCurrencies'
        ));
    }

    public function revenueCsv(Request $request)
    {
        $period = $request->input('period', 6);
        $currency = $request->input('currency', \App\Models\SystemSetting::first()->default_currency ?? 'TRY');

        $startDate = now()->subMonths($period)->startOfMonth();
        $endDate = now()->endOfMonth();

        $monthlyData = $this->getMonthlyData($startDate, $endDate, $currency);

        $filename = "gelir-analizi-{$currency}-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($handle, $monthlyData, $currency) {
            fputcsv($handle, ['Ay', "Kesilen Fatura ($currency)", "Tahsilat ($currency)", "Bekleyen ($currency)", 'Fatura Adeti']);

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
        $currency = $request->input('currency', \App\Models\SystemSetting::first()->default_currency ?? 'TRY');

        $startDate = now()->subMonths($period)->startOfMonth();
        $endDate = now()->endOfMonth();

        $monthlyData = $this->getMonthlyData($startDate, $endDate, $currency);

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
            'period',
            'currency'
        ));

        return $pdf->download("gelir-analizi-{$currency}.pdf");
    }

    private function getMonthlyData($startDate, $endDate, $currency = 'TRY')
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

        $driver = DB::connection()->getDriverName();
        $invoiceMonthFormat = match ($driver) {
            'sqlite' => "strftime('%Y-%m', issue_date)",
            'pgsql' => "TO_CHAR(issue_date, 'YYYY-MM')",
            default => "DATE_FORMAT(issue_date, '%Y-%m')",
        };

        $paymentMonthFormat = match ($driver) {
            'sqlite' => "strftime('%Y-%m', paid_at)",
            'pgsql' => "TO_CHAR(paid_at, 'YYYY-MM')",
            default => "DATE_FORMAT(paid_at, '%Y-%m')",
        };

        // Fetch Invoiced Data (Filtered by currency)
        $invoices = Invoice::whereBetween('issue_date', [$startDate, $endDate])
            ->where('currency', $currency)
            ->selectRaw("$invoiceMonthFormat as month, SUM(grand_total) as total, COUNT(*) as count, SUM(grand_total - paid_amount) as pending")
            ->groupBy('month')
            ->get();

        foreach ($invoices as $inv) {
            if (isset($months[$inv->month])) {
                $months[$inv->month]['invoiced'] = (float) $inv->total;
                $months[$inv->month]['invoice_count'] = $inv->count;
                $months[$inv->month]['pending'] = (float) $inv->pending;
            }
        }

        // Fetch Collected Data (Payments - Filtered by currency)
        $payments = Payment::whereBetween('paid_at', [$startDate, $endDate])
            ->where('currency', $currency)
            ->selectRaw("$paymentMonthFormat as month, SUM(amount) as total")
            ->groupBy('month')
            ->get();

        foreach ($payments as $pym) {
            if (isset($months[$pym->month])) {
                $months[$pym->month]['collected'] = (float) $pym->total;
            }
        }

        return collect($months)->values();
    }

    public function profit(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();
        $currency = $request->input('currency', 'TRY');

        // Manual Exchange Rates (Default 1 if same currency)
        $rateUsd = (float) $request->input('rate_usd', 34.50);
        $rateEur = (float) $request->input('rate_eur', 37.20);

        // 1. Get Invoices (Income)
        $invoices = Invoice::with(['items.service'])
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->where('currency', $currency)
            ->get();

        $report = [
            'total_revenue' => 0,
            'total_cost' => 0,
            'net_profit' => 0,
            'items' => []
        ];

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                // Calculate Revenue (Excluding VAT usually, but for simplicity taking line_subtotal)
                // line_subtotal = qty * unit_price (without tax)
                $revenue = $item->line_subtotal;

                // Calculate Cost
                $cost = 0;
                $buyingPrice = 0;
                $buyingCurrency = $currency;

                if ($item->service) {
                    $buyingPrice = (float) $item->service->buying_price;
                    $buyingCurrency = $item->service->buying_currency ?? $currency;

                    // Convert cost to report currency
                    if ($buyingCurrency !== $currency) {
                        if ($currency === 'TRY') {
                            if ($buyingCurrency === 'USD')
                                $buyingPrice *= $rateUsd;
                            elseif ($buyingCurrency === 'EUR')
                                $buyingPrice *= $rateEur;
                        } elseif ($currency === 'USD') {
                            if ($buyingCurrency === 'TRY')
                                $buyingPrice /= $rateUsd;
                            elseif ($buyingCurrency === 'EUR')
                                $buyingPrice = ($buyingPrice * $rateEur) / $rateUsd;
                        } elseif ($currency === 'EUR') {
                            if ($buyingCurrency === 'TRY')
                                $buyingPrice /= $rateEur;
                            elseif ($buyingCurrency === 'USD')
                                $buyingPrice = ($buyingPrice * $rateUsd) / $rateEur;
                        }
                    }

                    $cost = $buyingPrice * $item->qty;
                }

                $profit = $revenue - $cost;

                $report['total_revenue'] += $revenue;
                $report['total_cost'] += $cost;
                $report['net_profit'] += $profit;

                // Detail Item
                $report['items'][] = [
                    'invoice_number' => $invoice->number,
                    'invoice_date' => $invoice->issue_date->format('d.m.Y'),
                    'customer' => $invoice->customer->name ?? 'Silinmiş Müşteri',
                    'service_name' => $item->description,
                    'qty' => $item->qty,
                    'revenue' => $revenue,
                    'cost' => $cost,
                    'profit' => $profit,
                    'margin' => $revenue > 0 ? ($profit / $revenue) * 100 : 0
                ];
            }
        }

        // Sort by highest profit
        usort($report['items'], function ($a, $b) {
            return $b['profit'] <=> $a['profit'];
        });

        // Available currencies for filter dropdown
        $availableCurrencies = DB::table('invoices')->distinct()->pluck('currency');

        return view('reports.profit', compact(
            'report',
            'startDate',
            'endDate',
            'currency',
            'rateUsd',
            'rateEur',
            'availableCurrencies'
        ));
    }
}

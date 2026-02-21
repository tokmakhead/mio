<x-app-layout>
    @php
        $financeService = new \App\Services\FinanceService();
    @endphp
    <x-page-banner title="Gelir Analizi" subtitle="Dönemsel kesilen fatura, tahsilat ve bekleyen tutar analizi.">
        <x-slot name="actions">
            <div class="flex space-x-2">
                <a href="{{ route('reports.revenue.csv', ['period' => $period, 'currency' => $currency]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-lg backdrop-blur-sm transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    CSV İndir
                </a>
                <a href="{{ route('reports.revenue.pdf', ['period' => $period, 'currency' => $currency]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-lg backdrop-blur-sm transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    PDF İndir
                </a>
            </div>
        </x-slot>
    </x-page-banner>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Period Filter -->
                <div class="flex flex-wrap gap-2">
                    @foreach([1, 3, 6, 12] as $p)
                        <a href="{{ route('reports.revenue', ['period' => $p, 'currency' => $currency]) }}"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $period == $p ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                            Son {{ $p }} Ay
                        </a>
                    @endforeach
                </div>

                <!-- Currency Filter -->
                <div class="flex flex-wrap gap-2">
                    @foreach($availableCurrencies as $curr)
                        <a href="{{ route('reports.revenue', ['period' => $period, 'currency' => $curr]) }}"
                            class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $currency == $curr ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                            {{ $curr }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-kpi-card title="Toplam Kesilen ({{ $currency }})"
                    value="{{ $financeService->formatCurrency($totalInvoiced, $currency) }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' />
                <x-kpi-card title="Toplam Tahsilat ({{ $currency }})"
                    value="{{ $financeService->formatCurrency($totalCollected, $currency) }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
                <x-kpi-card title="Toplam Bekleyen ({{ $currency }})"
                    value="{{ $financeService->formatCurrency($totalPending, $currency) }}" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
                <x-kpi-card title="Ort. Fatura ({{ $currency }})"
                    value="{{ $financeService->formatCurrency($averageInvoice, $currency) }}" tone="accent"
                    icon='<svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>' />
            </div>

            <!-- Chart -->
            <x-card>
                <div class="h-80 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </x-card>

            <!-- Table -->
            <x-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Aylık Döküm ({{ $currency }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Dönem
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Kesilen Fatura</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Tahsilat</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Bekleyen</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Adet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($monthlyData as $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ $data['month'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-mono text-gray-700 dark:text-gray-300">
                                        {{ $financeService->formatCurrency($data['invoiced'], $currency) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-mono text-green-600">
                                        {{ $financeService->formatCurrency($data['collected'], $currency) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-mono text-warning-600">
                                        {{ $financeService->formatCurrency($data['pending'], $currency) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-mono text-gray-500">
                                        {{ $data['invoice_count'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('revenueChart').getContext('2d');

                const labels = @json($labels);
                const invoicedData = @json($invoicedData);
                const collectedData = @json($collectedData);
                const currentCurrency = @json($currency);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Kesilen Faturalar (' + currentCurrency + ')',
                                data: invoicedData,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Tahsilatlar (' + currentCurrency + ')',
                                data: collectedData,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: { family: "'Figtree', sans-serif", size: 12 }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function (context) {
                                        let label = context.dataset.label || '';
                                        if (label) { label += ': '; }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('tr-TR', { style: 'currency', currency: currentCurrency }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                                ticks: {
                                    callback: function (value) {
                                        return new Intl.NumberFormat('tr-TR', { notation: 'compact', compactDisplay: 'short' }).format(value) + ' ' + currentCurrency;
                                    }
                                }
                            },
                            x: { grid: { display: false } }
                        },
                        interaction: { mode: 'nearest', axis: 'x', intersect: false }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
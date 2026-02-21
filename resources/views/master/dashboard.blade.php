@extends('master.layout')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Master Dashboard</h1>
        <p class="text-gray-500 dark:text-gray-400">MIONEX finansal istihbarat ve dağıtım paneli.</p>
    </div>

    <!-- Financial KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- MRR -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aylık Tekrarlayan Gelir (MRR)</p>
            <div class="mt-2 space-y-1">
                @forelse($mrr as $val)
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($val->total, 2) }} {{ $val->currency }}
                    </p>
                @empty
                    <p class="text-2xl font-bold text-gray-400">0.00 USD</p>
                @endforelse
            </div>
            <div class="mt-4 flex items-center text-xs text-blue-600">
                <span>Abonelik bazlı canlı veri</span>
            </div>
        </div>

        <!-- One-Time Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Satış (Lifetime)</p>
            <div class="mt-2 space-y-1">
                @forelse($oneTimeRevenue as $val)
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($val->total, 2) }} {{ $val->currency }}
                    </p>
                @empty
                    <p class="text-2xl font-bold text-gray-400">0.00 USD</p>
                @endforelse
            </div>
        </div>

        <!-- Churn Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Churn Rate (İptal Oranı)</p>
            <p class="text-3xl font-bold {{ $churnRate > 10 ? 'text-red-600' : 'text-green-600' }} mt-1">%{{ $churnRate }}
            </p>
            <div class="mt-4 flex items-center text-xs text-gray-500">
                <span>Süresi dolan/İptal edilen lisanslar</span>
            </div>
        </div>

        <!-- Active Context -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktif Lisans Bilgisi</p>
            <div class="flex items-end justify-between mt-1">
                <p class="text-3xl font-bold text-gray-900 dark:text-white font-mono">
                    {{ $activeLicenses }}/{{ $totalLicenses }}
                </p>
                <div
                    class="w-10 h-10 bg-green-50 dark:bg-green-900/30 rounded flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Trend -->
        <div
            class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-6">Gelir Projeksiyonu & Trend</h3>
            <div id="revenueChart" style="min-height: 300px;"></div>
        </div>

        <!-- License Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-6">Lisans Tipi Dağılımı</h3>
            <div id="typeChart" style="min-height: 300px;"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Existing Version Dist & System Health -->
        <div class="block p-6 border border-gray-100 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 shadow-sm">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Versiyon Dağılımı (Canlı Veri)</h3>
            <div class="space-y-3">
                @forelse($versionDist as $dist)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $dist->version ?? 'v1.0.0' }}</span>
                            <span class="text-xs text-gray-500">{{ $dist->count }} Instance</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            @php $percentage = ($activeLicenses > 0) ? ($dist->count / $activeLicenses) * 100 : 0; @endphp
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">Henüz aktivasyon verisi yok.</p>
                @endforelse
            </div>
        </div>

        <div class="block p-6 border border-gray-100 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 shadow-sm">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Sistem Sağlığı & Kaynaklar</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 mb-1">Bellek Kullanımı</p>
                    <span class="text-lg font-bold text-blue-600">{{ $systemHealth['memory_usage'] }}</span>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 mb-1">Sunucu Yükü</p>
                    <span class="text-lg font-bold text-indigo-600">{{ $systemHealth['server_load'] }}</span>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 mb-1">Disk Kullanımı</p>
                    <span class="text-lg font-bold text-purple-600">{{ $systemHealth['disk_usage'] }}</span>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 mb-1">API Durumu</p>
                    <span class="text-lg font-bold text-green-600 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> OK
                    </span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Revenue Trend Chart
            var revenueOptions = {
                series: [{
                    name: 'Tahmini Gelir (USD)',
                    data: [31, 40, 28, 51, 42, 109, 100]
                }],
                chart: { height: 300, type: 'area', toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth' },
                colors: ['#2563eb'],
                xaxis: {
                    categories: ["Ock", "Şub", "Mar", "Nis", "May", "Haz", "Tem"],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                tooltip: { x: { format: 'dd/MM/yy HH:mm' } },
            };
            var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();

            // Type Distribution Chart
            var typeOptions = {
                series: {!! $typeDist->pluck('count') !!},
                labels: {!! $typeDist->pluck('type')->map(fn($v) => ucfirst($v)) !!},
                chart: { type: 'donut', height: 300, fontFamily: 'Inter, sans-serif' },
                colors: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                legend: { position: 'bottom' },
                responsive: [{
                    breakpoint: 480,
                    options: { chart: { width: 200 }, legend: { position: 'bottom' } }
                }]
            };
            var typeChart = new ApexCharts(document.querySelector("#typeChart"), typeOptions);
            typeChart.render();
        </script>
    @endpush
@endsection
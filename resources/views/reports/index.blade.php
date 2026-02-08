<x-app-layout>
    <!-- Page Banner (Standard System Design) -->
    <x-page-banner title="Analiz Merkezi" subtitle="İşletmenizin detaylı performans ve gelir raporlarını inceleyin." />

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Quick Stats Section (Using vStandard KPI Card Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-kpi-card title="Toplam Müşteri" value="{{ $stats['total_customers'] }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />
                <x-kpi-card title="Aktif Hizmet" value="{{ $stats['active_services'] }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>' />
                <x-kpi-card title="Toplam Gelir" value="₺{{ number_format($stats['total_revenue'], 0) }}" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
                <x-kpi-card title="Sağlayıcı" value="{{ $stats['total_providers'] }}" tone="danger"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>' />
            </div>

            <!-- Analysis Hubs (Functional Layout from 1821, Design from System) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Gelir Analizi -->
                <x-card class="flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-primary-50 dark:bg-primary-900/20 rounded-lg flex items-center justify-center text-primary-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="px-2 py-1 bg-primary-50 text-primary-600 text-[10px] font-bold rounded uppercase tracking-wider">Gelir</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Gelir Analizi</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 flex-grow">
                        Aylık, yıllık gelir trendlerini ve büyüme oranlarını görün.
                    </p>
                    <a href="{{ route('reports.revenue') }}"
                        class="inline-flex justify-center items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Görüntüle
                    </a>
                </x-card>

                <!-- Hizmet Analizi -->
                <x-card class="flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-success-50 dark:bg-success-900/20 rounded-lg flex items-center justify-center text-success-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="px-2 py-1 bg-success-50 text-success-600 text-[10px] font-bold rounded uppercase tracking-wider">Hizmet</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Hizmet Analizi</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 flex-grow">
                        Domain, hosting ve SSL hizmetlerinin dağılımını analiz edin.
                    </p>
                    <button
                        class="inline-flex justify-center items-center px-4 py-2 bg-success-600 hover:bg-success-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm cursor-not-allowed opacity-50">
                        Yakında
                    </button>
                </x-card>

                <!-- Müşteri Analizi -->
                <x-card class="flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-purple-50 dark:bg-purple-900/20 rounded-lg flex items-center justify-center text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="px-2 py-1 bg-purple-50 text-purple-600 text-[10px] font-bold rounded uppercase tracking-wider">Müşteri</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Müşteri Analizi</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 flex-grow">
                        Müşteri segmentasyonu ve hizmet kullanım oranlarını görün.
                    </p>
                    <button
                        class="inline-flex justify-center items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm cursor-not-allowed opacity-50">
                        Yakında
                    </button>
                </x-card>

                <!-- Sağlayıcı Analizi -->
                <x-card class="flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-orange-50 dark:bg-orange-900/20 rounded-lg flex items-center justify-center text-orange-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="px-2 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded uppercase tracking-wider">Sağlayıcı</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sağlayıcı Analizi</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 flex-grow">
                        Sağlayıcı performanslarını ve hizmet dağılımlarını analiz edin.
                    </p>
                    <button
                        class="inline-flex justify-center items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm cursor-not-allowed opacity-50">
                        Yakında
                    </button>
                </x-card>

                <!-- Genel Özet -->
                <x-card class="flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="px-2 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded uppercase tracking-wider">Özet</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Genel Özet</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 flex-grow">
                        Tüm metrikleri tek sayfada görün ve karşılaştırın.
                    </p>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Dashboard'a Git
                    </a>
                </x-card>
            </div>



        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Hoş geldin, {{ Auth::user()->name }}" subtitle="MIOLY Yönetim Paneli"
        metric="Bugün {{ now()->format('d M Y') }}" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Müşteri" value="{{ $totalCustomers }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />

                <x-kpi-card title="Aktif Hizmet" value="{{ $activeServicesCount }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>' />

                <x-kpi-card title="Aylık Gelir (MRR)" value="{{ number_format($mrr, 2) }}₺" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />

                <x-kpi-card title="Geciken Fatura" value="{{ $overdueInvoices }}" tone="danger"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Expiring Services Widget -->
                <x-card class="h-fit">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Vadesi Yaklaşan Hizmetler</h3>
                        <a href="{{ route('services.index', ['expiring_soon' => 1]) }}"
                            class="text-sm text-primary-600 dark:text-primary-400 hover:underline">Tümünü Gör</a>
                    </div>

                    @if($expiringServices->count() > 0)
                        <div class="space-y-4">
                            @foreach($expiringServices as $service)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 rounded-lg {{ \App\Models\Service::getTypeBadgeColor($service->type) }} flex items-center justify-center bg-opacity-10">
                                            <div class="font-bold text-xs uppercase">{{ substr($service->type, 0, 2) }}</div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $service->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->customer->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold {{ $service->expiry_color }}">
                                            {{ $service->end_date->format('d.m.Y') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->days_until_expiry }}
                                            gün kaldı</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Yakın zamanda bitecek hizmet yok.</p>
                        </div>
                    @endif
                </x-card>

                <!-- Quick Actions -->
                <x-card class="h-fit">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hızlı İşlemler</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('customers.create') }}"
                            class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors group flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Yeni Müşteri</span>
                        </a>
                        <a href="{{ route('services.create') }}"
                            class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-success-50 dark:hover:bg-success-900/10 transition-colors group flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-success-100 dark:bg-success-900/30 flex items-center justify-center text-success-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Yeni Hizmet</span>
                        </a>
                        <a href="#"
                            class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-warning-50 dark:hover:bg-warning-900/10 transition-colors group flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center text-warning-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Yeni Fatura</span>
                        </a>
                        <a href="{{ route('providers.create') }}"
                            class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-info-50 dark:hover:bg-info-900/10 transition-colors group flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-info-100 dark:bg-info-900/30 flex items-center justify-center text-info-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Sağlayıcı Ekle</span>
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <!-- Page Banner -->
    <x-page-banner title="Hoş geldin, {{ Auth::user()->name }}"
        subtitle="Operasyonel özet ve performans verileri | {{ now()->translatedFormat('l, d F Y') }}"
        metric="{{ number_format($mrr, 0) }}₺" label="Toplam MRR"
        tooltip="Aktif hizmetlerinizden elde ettiğiniz aylık tahmini gelirdir." />

    <!-- Main Content -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Announcements -->
            @if(isset($announcements) && count($announcements) > 0)
                <div class="space-y-4 mb-8">
                    @foreach($announcements as $announcement)
                        <div class="rounded-md p-4 border-l-4 shadow-sm
                                    @if($announcement['type'] == 'info') bg-blue-50 border-blue-400 dark:bg-blue-900/20 dark:border-blue-500
                                    @elseif($announcement['type'] == 'success') bg-green-50 border-green-400 dark:bg-green-900/20 dark:border-green-500
                                    @elseif($announcement['type'] == 'warning') bg-yellow-50 border-yellow-400 dark:bg-yellow-900/20 dark:border-yellow-500
                                    @elseif($announcement['type'] == 'danger') bg-red-50 border-red-400 dark:bg-red-900/20 dark:border-red-500
                                    @else bg-gray-50 border-gray-400 dark:bg-gray-800 dark:border-gray-500 @endif
                                ">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    @if($announcement['type'] == 'info')
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @elseif($announcement['type'] == 'success')
                                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @elseif($announcement['type'] == 'warning')
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @elseif($announcement['type'] == 'danger')
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium 
                                                @if($announcement['type'] == 'info') text-blue-800 dark:text-blue-200
                                                @elseif($announcement['type'] == 'success') text-green-800 dark:text-green-200
                                                @elseif($announcement['type'] == 'warning') text-yellow-800 dark:text-yellow-200
                                                @elseif($announcement['type'] == 'danger') text-red-800 dark:text-red-200
                                                @else text-gray-800 dark:text-gray-200 @endif
                                            ">
                                        {{ $announcement['title'] }}
                                    </h3>
                                    <div class="mt-2 text-sm 
                                                @if($announcement['type'] == 'info') text-blue-700 dark:text-blue-300
                                                @elseif($announcement['type'] == 'success') text-green-700 dark:text-green-300
                                                @elseif($announcement['type'] == 'warning') text-yellow-700 dark:text-yellow-300
                                                @elseif($announcement['type'] == 'danger') text-red-700 dark:text-red-300
                                                @else text-gray-700 dark:text-gray-300 @endif
                                            ">
                                        <p>{{ $announcement['message'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- 4 KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <x-kpi-card title="Toplam Müşteri" value="{{ $totalCustomers }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />

                <x-kpi-card title="Aktif Hizmet" value="{{ $activeServicesCount }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>' />

                <x-kpi-card title="Bu Ay Gelir" value="{{ number_format($thisMonthRevenue, 2) }}₺" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />

                <x-kpi-card title="Vadesi Geçen" value="{{ $overdueInvoices }}" tone="danger"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Revenue Trend -->
                <x-card class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gelir Trendi</h3>
                        <div class="flex space-x-2">
                            <button
                                class="px-3 py-1 text-xs font-medium rounded-md bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400">6
                                Ay</button>
                        </div>
                    </div>
                    <div class="h-[300px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </x-card>

                <!-- MRR Distribution -->
                <x-card>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">MRR Dağılımı</h3>
                    <div class="h-[300px] flex items-center justify-center">
                        <canvas id="mrrDistributionChart"></canvas>
                    </div>
                </x-card>
            </div>

            <!-- Financial Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div
                    class="bg-gray-50 dark:bg-gray-800/40 p-5 rounded-xl border border-gray-100 dark:border-gray-700/50">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Toplam Kesilen</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($villedTotal, 2) }}₺</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800/40 p-5 rounded-xl border border-gray-100 dark:border-gray-700/50">
                    <p class="text-xs font-medium text-green-600 dark:text-green-500 uppercase tracking-widest mb-1">
                        Toplam Tahsilat</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($collectedTotal, 2) }}₺
                    </p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800/40 p-5 rounded-xl border border-gray-100 dark:border-gray-700/50">
                    <p class="text-xs font-medium text-rose-600 dark:text-rose-500 uppercase tracking-widest mb-1">
                        Bekleyen Tahsilat</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingTotal, 2) }}₺
                    </p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800/40 p-5 rounded-xl border border-gray-100 dark:border-gray-700/50">
                    <p
                        class="text-xs font-medium text-primary-600 dark:text-primary-500 uppercase tracking-widest mb-1">
                        Ortalama Fatura</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($avgInvoice, 2) }}₺</p>
                </div>
            </div>

            <!-- Detailed Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <!-- Expiring Services -->
                <x-card padding="p-0">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Vadesi Yaklaşan Hizmetler</h3>
                        <a href="{{ route('services.index', ['expiring_soon' => 1]) }}"
                            class="text-sm text-primary-600 hover:underline">Tümünü Gör</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400 font-medium">
                                <tr>
                                    <th class="px-6 py-3">Hizmet / Müşteri</th>
                                    <th class="px-6 py-3">Bitiş Tarihi</th>
                                    <th class="px-6 py-3 text-right">Durum</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/40">
                                @forelse($expiringServices as $service)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $service->name }}
                                            </div>
                                            <div class="text-gray-500 text-xs">{{ $service->customer->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-900 dark:text-white">
                                                {{ $service->end_date->format('d.m.Y') }}
                                            </div>
                                            <div class="text-gray-500 text-xs">{{ $service->days_until_expiry }} gün kaldı
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                                                                    @if($service->days_until_expiry < 30) bg-rose-50 border-rose-200 text-rose-700 dark:bg-rose-900/20 dark:border-rose-800 dark:text-rose-400
                                                                                    @elseif($service->days_until_expiry < 60) bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-400
                                                                                    @else bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400 @endif">
                                                {{ $service->days_until_expiry < 30 ? 'Acil' : ($service->days_until_expiry < 60 ? 'Yarım' : 'Normal') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">Kayıt bulunamadı.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>

                <!-- Recent Activities -->
                <x-card padding="p-0">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Son Aktiviteler</h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($recentActivities as $activity)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span
                                                    class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800 
                                                                                            @php
                                                                                                $color = 'bg-gray-400';
                                                                                                if (str_contains($activity->action, 'created'))
                                                                                                    $color = 'bg-green-500';
                                                                                                if (str_contains($activity->action, 'updated'))
                                                                                                    $color = 'bg-blue-500';
                                                                                                if (str_contains($activity->action, 'deleted'))
                                                                                                    $color = 'bg-red-500';
                                                                                                if (str_contains($activity->action, 'sent'))
                                                                                                    $color = 'bg-indigo-500';
                                                                                                if (str_contains($activity->action, 'login'))
                                                                                                    $color = 'bg-primary-500';
                                                                                            @endphp
                                                                                            {{ $color }}">
                                                        @php
                                                            $icon = 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z';
                                                            if (str_contains($activity->action, 'invoice'))
                                                                $icon = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
                                                            if (str_contains($activity->action, 'customer'))
                                                                $icon = 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857';
                                                        @endphp
                                                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="{{ $icon }}"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                                            <strong>{{ $activity->actor ? $activity->actor->name : 'Sistem' }}</strong>,
                                                            {{ $activity->action == 'user.login' ? 'Giriş yaptı' : '' }}
                                                            {{ $activity->action == 'user.logout' ? 'Çıkış yaptı' : '' }}
                                                            {{ str_contains($activity->action, 'created') ? 'yeni bir kayıt oluşturdu' : '' }}
                                                            {{ str_contains($activity->action, 'updated') ? 'kaydı güncelledi' : '' }}
                                                            {{ str_contains($activity->action, 'deleted') ? 'bir kaydı sildi' : '' }}
                                                            {{ str_contains($activity->action, 'sent') ? 'dokümanı gönderdi' : '' }}
                                                            {{ str_contains($activity->action, 'paid') ? 'faturanın ödendiğini işaretledi' : '' }}
                                                        </p>
                                                        <p
                                                            class="text-xs text-primary-600 dark:text-primary-400 font-medium">
                                                            {{ $activity->metadata['name'] ?? '' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                        <time
                                                            datetime="{{ $activity->created_at }}">{{ $activity->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Quick Actions Grid -->
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Hızlı Erişim</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('customers.create') }}"
                        class="group p-6 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700/50 hover:bg-primary-50 dark:hover:bg-primary-900/10 hover:border-primary-200 dark:hover:border-primary-800 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 group-hover:scale-110 transition-transform mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Yeni Müşteri</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Yeni bir portföy kaydı
                            oluşturun.</p>
                    </a>

                    <a href="{{ route('invoices.create') }}"
                        class="group p-6 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700/50 hover:bg-success-50 dark:hover:bg-success-900/10 hover:border-success-200 dark:hover:border-success-800 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-xl bg-success-100 dark:bg-success-900/30 flex items-center justify-center text-success-600 group-hover:scale-110 transition-transform mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Yeni Fatura</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Hızlıca fatura kesin ve
                            gönderin.</p>
                    </a>

                    <a href="{{ route('services.create') }}"
                        class="group p-6 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700/50 hover:bg-warning-50 dark:hover:bg-warning-900/10 hover:border-warning-200 dark:hover:border-warning-800 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-xl bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center text-warning-600 group-hover:scale-110 transition-transform mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Yeni Hizmet</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Müşteriye hizmet tanımlayın.
                        </p>
                    </a>

                    <a href="{{ route('accounting.reconciliation.index') }}"
                        class="group p-6 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700/50 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:border-rose-200 dark:hover:border-rose-800 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Mutabakat</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Finansal tutarlılığı kontrol
                            edin.</p>
                    </a>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Revenue Chart
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: @json(collect($revenueTrend)->pluck('month')),
                    datasets: [
                        {
                            label: 'Kesilen (₺)',
                            data: @json(collect($revenueTrend)->pluck('billed')),
                            borderColor: '#a82244',
                            backgroundColor: 'rgba(168, 34, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#a82244',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'Tahsil Edilen (₺)',
                            data: @json(collect($revenueTrend)->pluck('collected')),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 6, font: { size: 12 } } },
                        tooltip: { backgroundColor: '#1f2937', padding: 12, titleFont: { size: 14 } }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(156, 163, 175, 0.1)' },
                            ticks: { font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });

            // MRR Distribution Chart
            const mrrCtx = document.getElementById('mrrDistributionChart').getContext('2d');
            new Chart(mrrCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($mrrDistribution->pluck('label')),
                    datasets: [{
                        data: @json($mrrDistribution->pluck('value')),
                        backgroundColor: [
                            '#a82244', '#10b981', '#f59e0b', '#3b82f6', '#8b5cf6'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, font: { size: 11 } } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-page-banner :title="__('Kâr / Zarar Analizi')" :subtitle="__('Operasyonel kârlılık ve hizmet bazlı performans detayları')">
        <x-slot name="actions">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-white/10 text-white text-xs font-bold uppercase tracking-widest rounded-lg backdrop-blur-sm border border-white/20">
                    {{ $currency }} BAZLI RAPOR
                </span>
            </div>
        </x-slot>
    </x-page-banner>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filtreler --}}
            <x-card>
                <form method="GET" action="{{ route('reports.profit') }}"
                    class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 uppercase tracking-wide">Başlangıç</label>
                        <x-text-input id="start_date" type="date" name="start_date"
                            :value="$startDate ? $startDate->format('Y-m-d') : ''"
                            class="block w-full" />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 uppercase tracking-wide">Bitiş</label>
                        <x-text-input id="end_date" type="date" name="end_date"
                            :value="$endDate ? $endDate->format('Y-m-d') : ''"
                            class="block w-full" />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 uppercase tracking-wide">Para Birimi</label>
                        <select name="currency"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 text-sm">
                            @foreach(['TRY', 'USD', 'EUR'] as $curr)
                                <option value="{{ $curr }}" {{ $currency === $curr ? 'selected' : '' }}>{{ $curr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 uppercase tracking-wide">USD Kuru</label>
                        <x-text-input id="rate_usd" type="number" step="0.01" name="rate_usd" :value="$rateUsd"
                            class="block w-full font-mono" />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 uppercase tracking-wide">EUR Kuru</label>
                        <x-text-input id="rate_eur" type="number" step="0.01" name="rate_eur" :value="$rateEur"
                            class="block w-full font-mono" />
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            ANALİZ ET
                        </button>
                    </div>
                </form>
            </x-card>

            {{-- Özet KPI Kartları --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-kpi-card
                    title="Toplam Ciro"
                    value="{{ number_format($report['total_revenue'], 2, ',', '.') }} {{ $currency }}"
                    tone="primary"
                    sub-value="KDV Hariç Gelir"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />

                <x-kpi-card
                    title="Toplam Maliyet"
                    value="{{ number_format($report['total_cost'], 2, ',', '.') }} {{ $currency }}"
                    tone="danger"
                    sub-value="Teorik Alış Tutarı"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />

                <x-kpi-card
                    title="Net Operasyonel Kâr"
                    value="{{ number_format($report['net_profit'], 2, ',', '.') }} {{ $currency }}"
                    tone="{{ $report['net_profit'] >= 0 ? 'success' : 'danger' }}"
                    sub-value="{{ $report['net_profit'] >= 0 ? 'Kârda' : 'Zararda' }}"
                    icon='<svg class="w-6 h-6 {{ $report["net_profit"] >= 0 ? "text-success-600 dark:text-success-400" : "text-danger-600 dark:text-danger-400" }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>' />

                <x-kpi-card
                    title="Ortalama Marj"
                    value="%{{ $report['total_revenue'] > 0 ? number_format(($report['net_profit'] / $report['total_revenue']) * 100, 1) : 0 }}"
                    tone="warning"
                    sub-value="Kârlılık Oranı"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>' />
            </div>

            {{-- Detay Tablosu --}}
            <x-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Hizmet Bazlı Kârlılık Detayı</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($report['items']) }} Kayıt Listeleniyor</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                                <th class="px-4 py-3">Fatura & Tarih</th>
                                <th class="px-4 py-3">Müşteri</th>
                                <th class="px-4 py-3">Hizmet</th>
                                <th class="px-4 py-3 text-right">Gelir</th>
                                <th class="px-4 py-3 text-right">Maliyet</th>
                                <th class="px-4 py-3 text-right">Net Kâr</th>
                                <th class="px-4 py-3 text-right">Marj</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @forelse($report['items'] as $item)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors duration-150">
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">#{{ $item['invoice_number'] }}</div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500">{{ $item['invoice_date'] }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $item['customer'] }}</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($item['service_name'], 40) }}</span>
                                            <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-400">x{{ $item['qty'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100 font-mono">{{ number_format($item['revenue'], 2, ',', '.') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <span class="text-sm font-semibold text-danger-600 dark:text-danger-400 font-mono">-{{ number_format($item['cost'], 2, ',', '.') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <span class="text-sm font-bold {{ $item['profit'] >= 0 ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }} font-mono">
                                            {{ number_format($item['profit'], 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            {{ $item['margin'] > 50
                                                ? 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400'
                                                : ($item['margin'] > 20
                                                    ? 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400'
                                                    : 'bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400') }}">
                                            %{{ number_format($item['margin'], 1) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Veri Bulunamadı</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Seçilen kriterlere uygun kârlılık verisi mevcut değil.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>
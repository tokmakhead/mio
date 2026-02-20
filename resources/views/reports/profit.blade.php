<x-app-layout>
    <x-page-banner :title="__('Kâr / Zarar Analizi')" :subtitle="__('Operasyonel kârlılık ve hizmet bazlı performans detayları')">
        <x-slot name="actions">
            <div class="flex items-center gap-3">
                <span
                    class="px-3 py-1 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-primary-100 dark:border-primary-800/30">
                    {{ $currency }} BAZLI RAPOR
                </span>
            </div>
        </x-slot>
    </x-page-banner>

    <div class="py-10">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- Filtreler --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700/50">
                <form method="GET" action="{{ route('reports.profit') }}"
                    class="grid grid-cols-1 md:grid-cols-6 gap-6 items-end">

                    {{-- Tarih Aralığı --}}
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">BAŞLANGIÇ</label>
                        <x-text-input id="start_date" type="date" name="start_date" :value="$startDate->format('Y-m-d')"
                            class="block w-full !rounded-xl !border-gray-100 dark:!border-gray-700" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">BİTİŞ</label>
                        <x-text-input id="end_date" type="date" name="end_date" :value="$endDate->format('Y-m-d')"
                            class="block w-full !rounded-xl !border-gray-100 dark:!border-gray-700" />
                    </div>

                    {{-- Raport Para Birimi --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">PARA
                            BİRİMİ</label>
                        <select name="currency"
                            class="block w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-xl shadow-sm text-sm font-bold">
                            @foreach(['TRY', 'USD', 'EUR'] as $curr)
                                <option value="{{ $curr }}" {{ $currency === $curr ? 'selected' : '' }}>{{ $curr }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kur Alanları --}}
                    <div x-data="{ curr: '{{ $currency }}' }" class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">USD
                            KURU</label>
                        <x-text-input id="rate_usd" type="number" step="0.01" name="rate_usd" :value="$rateUsd"
                            class="block w-full !rounded-xl !border-gray-100 dark:!border-gray-700 !font-mono"
                            x-bind:disabled="curr === 'USD'" />
                    </div>
                    <div x-data="{ curr: '{{ $currency }}' }" class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">EUR
                            KURU</label>
                        <x-text-input id="rate_eur" type="number" step="0.01" name="rate_eur" :value="$rateEur"
                            class="block w-full !rounded-xl !border-gray-100 dark:!border-gray-700 !font-mono"
                            x-bind:disabled="curr === 'EUR'" />
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full h-[42px] bg-primary-600 hover:bg-primary-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-primary-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            ANALİZ ET
                        </button>
                    </div>
                </form>
            </div>

            {{-- Özet Kartları --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div
                    class="relative group bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-full"></div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">TOPLAM CİRO
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-gray-100 tracking-tight">
                            {{ number_format($report['total_revenue'], 2, ',', '.') }} <span
                                class="text-sm font-bold opacity-40">{{ $currency }}</span>
                        </div>
                        <div class="mt-2 text-[10px] font-bold text-blue-500 uppercase tracking-tighter">KDV HARİÇ GELİR
                        </div>
                    </div>
                </div>

                <div
                    class="relative group bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-red-500/5 rounded-bl-full"></div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">TOPLAM MALİYET
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-gray-100 tracking-tight">
                            {{ number_format($report['total_cost'], 2, ',', '.') }} <span
                                class="text-sm font-bold opacity-40">{{ $currency }}</span>
                        </div>
                        <div class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-tighter">TEORİK ALIŞ
                            TUTARI
                        </div>
                    </div>
                </div>

                <div
                    class="relative group bg-white dark:bg-gray-800 rounded-3xl p-8 border-2 border-primary-500/10 shadow-xl shadow-primary-500/5 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-primary-500/10 rounded-bl-full"></div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-primary-500 uppercase tracking-widest mb-4">NET
                            OPERASYONEL
                            KÂR</div>
                        <div
                            class="text-3xl font-black {{ $report['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }} tracking-tight">
                            {{ number_format($report['net_profit'], 2, ',', '.') }} <span
                                class="text-sm font-bold opacity-40">{{ $currency }}</span>
                        </div>
                        <div
                            class="mt-2 text-[10px] font-bold {{ $report['net_profit'] >= 0 ? 'text-green-500' : 'text-red-500' }} uppercase tracking-tighter">
                            {{ $report['net_profit'] >= 0 ? 'KÂRDA' : 'ZARARDA' }}
                        </div>
                    </div>
                </div>

                <div
                    class="relative group bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-500/5 rounded-bl-full"></div>
                    <div class="relative">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">ORTALAMA MARJ
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-gray-100 tracking-tight">
                            %{{ $report['total_revenue'] > 0 ? number_format(($report['net_profit'] / $report['total_revenue']) * 100, 1) : 0 }}
                        </div>
                        <div class="mt-2 text-[10px] font-bold text-yellow-500 uppercase tracking-tighter">KÂRLILIK
                            ORANI
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detay Tablosu --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="p-8 border-b border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
                    <h3 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest">Hizmet Bazlı
                        Kârlılık Detayı</h3>
                    <span class="text-[10px] font-bold text-gray-400">{{ count($report['items']) }} Kayıt
                        Listeleniyor</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50 dark:bg-gray-900/20">
                                <th class="px-8 py-5">FATURA & TARİH</th>
                                <th class="px-8 py-5">MÜŞTERİ</th>
                                <th class="px-8 py-5">HİZMET</th>
                                <th class="px-8 py-5 text-right">GELİR</th>
                                <th class="px-8 py-5 text-right">MALİYET</th>
                                <th class="px-8 py-5 text-right">NET KÂR</th>
                                <th class="px-8 py-5 text-right">MARJ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @forelse($report['items'] as $item)
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-all duration-200">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            #{{ $item['invoice_number'] }}</div>
                                        <div class="text-[10px] font-medium text-gray-400">{{ $item['invoice_date'] }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $item['customer'] }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ Str::limit($item['service_name'], 40) }}</span>
                                            <span
                                                class="px-1.5 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 text-[10px] font-black text-gray-500">x{{ $item['qty'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span
                                            class="text-sm font-bold text-gray-900 dark:text-gray-100 font-mono">{{ number_format($item['revenue'], 2, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span
                                            class="text-sm font-bold text-red-500 font-mono">-{{ number_format($item['cost'], 2, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span
                                            class="text-sm font-black {{ $item['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-mono">
                                            {{ number_format($item['profit'], 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black {{ $item['margin'] > 50 ? 'bg-green-50 text-green-600' : ($item['margin'] > 20 ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600') }}">
                                            %{{ number_format($item['margin'], 1) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">VERİ
                                                BULUNAMADI</h3>
                                            <p class="text-xs text-gray-400 mt-1">Seçilen kriterlere uygun herhangi bir
                                                kârlılık
                                                verisi mevcut değil.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
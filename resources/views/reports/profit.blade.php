<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kâr / Zarar Analizi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filtreler --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('reports.profit') }}"
                    class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">

                    {{-- Tarih Aralığı --}}
                    <div>
                        <x-input-label for="start_date" :value="__('Başlangıç')" />
                        <x-text-input id="start_date" type="date" name="start_date" :value="$startDate->format('Y-m-d')"
                            class="block mt-1 w-full" />
                    </div>
                    <div>
                        <x-input-label for="end_date" :value="__('Bitiş')" />
                        <x-text-input id="end_date" type="date" name="end_date" :value="$endDate->format('Y-m-d')"
                            class="block mt-1 w-full" />
                    </div>

                    {{-- Raport Para Birimi --}}
                    <div>
                        <x-input-label for="currency" :value="__('Para Birimi')" />
                        <select name="currency"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach(['TRY', 'USD', 'EUR'] as $curr)
                                <option value="{{ $curr }}" {{ $currency === $curr ? 'selected' : '' }}>{{ $curr }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kur Alanları --}}
                    <div x-data="{ curr: '{{ $currency }}' }">
                        <x-input-label for="rate_usd" :value="__('USD Kuru')" />
                        <x-text-input id="rate_usd" type="number" step="0.01" name="rate_usd" :value="$rateUsd"
                            class="block mt-1 w-full" x-bind:disabled="curr === 'USD'" />
                    </div>
                    <div x-data="{ curr: '{{ $currency }}' }">
                        <x-input-label for="rate_eur" :value="__('EUR Kuru')" />
                        <x-text-input id="rate_eur" type="number" step="0.01" name="rate_eur" :value="$rateEur"
                            class="block mt-1 w-full" x-bind:disabled="curr === 'EUR'" />
                    </div>

                    <div>
                        <x-primary-button class="w-full justify-center">
                            {{ __('Analiz Et') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Özet Kartları --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Ciro (KDV Hariç)</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ number_format($report['total_revenue'], 2) }} <span
                            class="text-lg text-gray-500">{{ $currency }}</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Maliyet (Tahmini)</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ number_format($report['total_cost'], 2) }} <span
                            class="text-lg text-gray-500">{{ $currency }}</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Kâr</div>
                    <div class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format($report['net_profit'], 2) }} <span
                            class="text-lg text-gray-500">{{ $currency }}</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Kâr Marjı</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                        %{{ $report['total_revenue'] > 0 ? number_format(($report['net_profit'] / $report['total_revenue']) * 100, 1) : 0 }}
                    </div>
                </div>
            </div>

            {{-- Detay Tablosu --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Hizmet Bazlı Kârlılık Detayı
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Fatura</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Müşteri</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Hizmet</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Gelir</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Maliyet</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kâr</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Marj</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($report['items'] as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            #{{ $item['invoice_number'] }} <br>
                                            <span class="text-xs text-gray-500">{{ $item['invoice_date'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $item['customer'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($item['service_name'], 30) }}
                                            <span
                                                class="text-xs bg-gray-100 dark:bg-gray-700 px-1 rounded">x{{ $item['qty'] }}</span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($item['revenue'], 2) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 dark:text-red-400">
                                            -{{ number_format($item['cost'], 2) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $item['profit'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ number_format($item['profit'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['margin'] > 50 ? 'bg-green-100 text-green-800' : ($item['margin'] > 20 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                %{{ number_format($item['margin'], 1) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Seçilen tarih aralığında veri bulunamadı.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
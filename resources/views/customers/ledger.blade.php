<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cari Hesap Ekstresi') }} - {{ $customer->name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: '{{ $currencies->first() ?? 'TRY' }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Financial Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @foreach($summaryBalances as $currency => $balance)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 {{ $balance > 0 ? 'border-red-500' : ($balance < 0 ? 'border-green-500' : 'border-gray-500') }}">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                           {{ $currency }} Bakiyesi
                        </div>
                        <div class="mt-2 text-3xl font-bold {{ $balance > 0 ? 'text-red-600' : ($balance < 0 ? 'text-green-600' : 'text-gray-900 dark:text-gray-100') }}">
                            {{ number_format(abs($balance), 2) }} <span class="text-lg">{{ $currency }}</span>
                        </div>
                        <div class="mt-1 text-xs font-semibold uppercase tracking-wider {{ $balance > 0 ? 'text-red-500' : ($balance < 0 ? 'text-green-500' : 'text-gray-400') }}">
                            {{ $balance > 0 ? 'Borçlu' : ($balance < 0 ? 'Alacaklı' : 'Bakiye Yok') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Tabs Header -->
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        @forelse($currencies as $currency)
                            <button 
                                @click="activeTab = '{{ $currency }}'"
                                :class="activeTab === '{{ $currency }}'
                                    ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                                {{ $currency }} Ekstresi
                            </button>
                        @empty
                             <button class="border-primary-500 text-primary-600 dark:text-primary-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                İşlem Yok
                            </button>
                        @endforelse
                    </nav>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @forelse($groupedEntries as $currency => $entries)
                        <div x-show="activeTab === '{{ $currency }}'" x-transition:enter.duration.300ms>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tarih</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">İşlem Türü</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Açıklama</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Borç</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alacak</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bakiye ({{ $currency }})</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($entries as $entry)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $entry->occurred_at->format('d.m.Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if ($entry->type === 'debit')
                                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                                            Borç (Fatura)
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                            Alacak (Ödeme)
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $entry->description }}
                                                    @if ($entry->ref_type === 'App\Models\Invoice')
                                                        <a href="{{ route('invoices.show', $entry->ref_id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 hover:underline ms-2 text-xs font-medium transition-colors">Inv #{{ $entry->ref->number ?? $entry->ref_id }}</a>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                                    @if ($entry->type === 'debit')
                                                        {{ number_format($entry->amount, 2) }} {{ $entry->currency }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                                    @if ($entry->type === 'credit')
                                                        {{ number_format($entry->amount, 2) }} {{ $entry->currency }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $entry->balance > 0 ? 'text-red-600 dark:text-red-400' : ($entry->balance < 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-gray-100') }}">
                                                    {{ number_format(abs($entry->balance), 2) }} {{ $entry->currency }}
                                                    <span class="text-xs font-normal text-gray-500 ml-1">
                                                        {{ $entry->balance > 0 ? '(B)' : ($entry->balance < 0 ? '(A)' : '') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">İşlem Bulunamadı</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bu müşteriye ait henüz herhangi bir işlem kaydı yok.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

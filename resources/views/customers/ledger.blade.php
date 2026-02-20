<x-app-layout>
    <x-page-banner :title="__('Cari Hesap Ekstresi')" :subtitle="$customer->name">
        <x-slot name="actions">
            <a href="{{ route('customers.ledger.pdf', $customer->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                PDF İndir
            </a>
        </x-slot>
    </x-page-banner>

    <div class="py-8" x-data="{ activeTab: '{{ $currencies->first() ?? 'TRY' }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                @foreach($summaryBalances as $currency => $balance)
                    <div class="relative group bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <!-- Decoration -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ $balance > 0 ? 'from-red-500/5 to-transparent' : ($balance < 0 ? 'from-green-500/5 to-transparent' : 'from-gray-500/5 to-transparent') }} rounded-bl-full"></div>
                        
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                    {{ $currency }} BAKİYESİ
                                </span>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $balance > 0 ? 'bg-red-50 text-red-500' : ($balance < 0 ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="text-4xl font-black {{ $balance > 0 ? 'text-red-600' : ($balance < 0 ? 'text-green-600' : 'text-gray-900 dark:text-gray-100') }} tracking-tight">
                                {{ number_format(abs($balance), 2, ',', '.') }} <span class="text-xl font-bold opacity-60">{{ $currency }}</span>
                            </div>
                            
                            <div class="mt-4 flex items-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $balance > 0 ? 'bg-red-100 text-red-600' : ($balance < 0 ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500') }}">
                                    {{ $balance > 0 ? 'Borçlu (Debit)' : ($balance < 0 ? 'Alacaklı (Credit)' : 'Bakiye Yok') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Transactions Section -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Tabs Navigation -->
                <div class="px-8 pt-8 bg-gray-50/50 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center space-x-8">
                        @forelse($currencies as $currency)
                            <button 
                                @click="activeTab = '{{ $currency }}'"
                                :class="activeTab === '{{ $currency }}'
                                    ? 'text-primary-600 border-primary-500'
                                    : 'text-gray-400 border-transparent hover:text-gray-600 hover:border-gray-300'"
                                class="pb-6 border-b-4 text-xs font-black uppercase tracking-widest transition-all duration-300">
                                {{ $currency }} Ekstresi
                            </button>
                        @empty
                            <button class="pb-6 border-b-4 border-primary-500 text-primary-600 text-xs font-black uppercase tracking-widest">
                                İşlem Kaydı Yok
                            </button>
                        @endforelse
                    </div>
                </div>

                <div class="p-8">
                    @forelse($groupedEntries as $currency => $entries)
                        <div x-show="activeTab === '{{ $currency }}'" x-transition:enter.duration.500ms>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                            <th class="px-6 py-4">Tarih</th>
                                            <th class="px-6 py-4">Tür</th>
                                            <th class="px-6 py-4">Açıklama</th>
                                            <th class="px-6 py-4 text-right">Borç</th>
                                            <th class="px-6 py-4 text-right">Alacak</th>
                                            <th class="px-6 py-4 text-right">Bakiye</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                        @foreach ($entries as $entry)
                                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-all duration-200">
                                                <td class="px-6 py-5">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $entry->occurred_at ? $entry->occurred_at->format('d/m/Y') : '-' }}</div>
                                                    <div class="text-[10px] font-medium text-gray-400">{{ $entry->occurred_at ? $entry->occurred_at->format('H:i') : '' }}</div>
                                                </td>
                                                <td class="px-6 py-5">
                                                    @if ($entry->type === 'debit')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800/30">
                                                            <span class="w-1 h-1 rounded-full bg-red-500 mr-1.5"></span>
                                                            BORÇ
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400 border border-green-100 dark:border-green-800/30">
                                                            <span class="w-1 h-1 rounded-full bg-green-500 mr-1.5"></span>
                                                            ALACAK
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $entry->description }}</span>
                                                        @if ($entry->ref_type === 'App\Models\Invoice')
                                                            <a href="{{ route('invoices.show', $entry->ref_id) }}" 
                                                               class="mt-1 inline-flex items-center text-[10px] font-bold text-primary-500 hover:text-primary-700 transition-colors">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                </svg>
                                                                Fatura #{{ $entry->ref->number ?? $entry->ref_id }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5 text-right font-mono text-sm {{ $entry->type === 'debit' ? 'text-red-600 font-bold' : 'text-gray-400' }}">
                                                    {{ $entry->type === 'debit' ? number_format($entry->amount, 2, ',', '.') : '-' }}
                                                </td>
                                                <td class="px-6 py-5 text-right font-mono text-sm {{ $entry->type === 'credit' ? 'text-green-600 font-bold' : 'text-gray-400' }}">
                                                    {{ $entry->type === 'credit' ? number_format($entry->amount, 2, ',', '.') : '-' }}
                                                </td>
                                                <td class="px-6 py-5 text-right">
                                                    <div class="flex flex-col items-end">
                                                        <span class="text-sm font-black {{ $entry->balance > 0 ? 'text-red-600' : ($entry->balance < 0 ? 'text-green-600' : 'text-gray-900 dark:text-white') }}">
                                                            {{ number_format(abs($entry->balance), 2, ',', '.') }}
                                                        </span>
                                                        <span class="text-[9px] font-bold opacity-40 uppercase tracking-tighter">
                                                            {{ $entry->balance > 0 ? 'Borç (B)' : ($entry->balance < 0 ? 'Alacak (A)' : 'Dengede') }}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-24">
                            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">İşlem Kaydı Bulunamadı</h3>
                            <p class="text-sm text-gray-400 font-medium">Bu cari hesaba ait henüz herhangi bir finansal hareket yok.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

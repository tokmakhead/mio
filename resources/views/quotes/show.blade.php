<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Teklif Detayı" subtitle="{{ $quote->number }} - {{ $quote->customer->name ?? '-' }}" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex items-center space-x-2">
                    <span
                        class="px-3 py-1 text-sm font-semibold rounded-full {{ \App\Models\Quote::getStatusColor($quote->status) }}">
                        {{ \App\Models\Quote::getStatusLabel($quote->status) }}
                    </span>
                    @if($quote->sent_at)
                        <span class="text-xs text-gray-500">Gönderildi:
                            {{ $quote->sent_at ? $quote->sent_at->format('d.m.Y H:i') : '-' }}</span>
                    @endif
                </div>

                <div class="flex space-x-3">
                    @if($quote->status === 'draft')
                        <a href="{{ route('quotes.edit', $quote) }}"
                            class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            <span>Düzenle</span>
                        </a>

                        <form action="{{ route('quotes.send', $quote) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Mail Gönder</span>
                            </button>
                        </form>
                    @endif

                    @if($quote->status === 'sent')
                        <form action="{{ route('quotes.send', $quote) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-400 font-bold" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Maili Tekrar Gönder</span>
                            </button>
                        </form>
                    @endif

                    @if($quote->status === 'sent')
                        <form action="{{ route('quotes.accept', $quote) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-success-600 hover:bg-success-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Kabul Et</span>
                            </button>
                        </form>
                    @endif

                    @if($quote->status === 'accepted')
                        <form action="{{ route('quotes.convert', $quote) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Faturaya Dönüştür</span>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('quotes.pdf', $quote) }}" target="_blank"
                        class="px-4 py-2 bg-danger-600 hover:bg-danger-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17h6m-6-4h6m-6-4h6"></path>
                        </svg>
                        <span>PDF İndir</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Details Card -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card>
                        <div
                            class="flex flex-col md:flex-row justify-between mb-8 pb-8 border-b border-gray-100 dark:border-gray-800">
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Müşteri
                                    Bilgileri</h4>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $quote->customer->name ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $quote->customer->email ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $quote->customer->phone ?? '-' }}</p>
                            </div>
                            <div class="mt-4 md:mt-0 md:text-right">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Teklif
                                    Tarihleri</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Oluşturma: <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $quote->created_at ? $quote->created_at->format('d.m.Y') : '-' }}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Son Geçerlilik: <span
                                        class="font-semibold text-danger-600">{{ $quote->valid_until ? $quote->valid_until->format('d.m.Y') : '-' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-800">
                                        <th class="pb-4">Açıklama</th>
                                        <th class="pb-4 text-center">Miktar</th>
                                        <th class="pb-4 text-right">Birim Fiyat</th>
                                        <th class="pb-4 text-right">KDV %</th>
                                        <th class="pb-4 text-right">Toplam</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                                    @foreach($quote->items as $item)
                                        <tr class="text-sm">
                                            <td class="py-4">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $item->description }}
                                                </div>
                                                @if($item->service)
                                                    <div class="text-xs text-primary-600">
                                                        {{ $item->service->identifier_code ?? '-' }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-4 text-center text-gray-600 dark:text-gray-400">
                                                {{ number_format($item->qty, 2) }}
                                            </td>
                                            <td class="py-4 text-right text-gray-600 dark:text-gray-400">
                                                {{ number_format($item->unit_price, 2) }} {{ $quote->currency }}
                                            </td>
                                            <td class="py-4 text-right text-gray-600 dark:text-gray-400">
                                                {{ $item->vat_rate }}%
                                            </td>
                                            <td class="py-4 text-right font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($item->line_total, 2) }} {{ $quote->currency }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-card>

                    @if($quote->notes)
                        <x-card>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Notlar</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $quote->notes }}</p>
                        </x-card>
                    @endif
                </div>

                <!-- Totals Card -->
                <div class="lg:col-span-1">
                    <x-card class="bg-gray-50 dark:bg-gray-800/50 border-gray-100 dark:border-gray-800">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 text-center">Teklif
                            Özeti</h4>

                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Ara Toplam</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ number_format($quote->subtotal, 2) }}
                                    {{ $quote->currency }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">KDV Toplam</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ number_format($quote->tax_total, 2) }}
                                    {{ $quote->currency }}</span>
                            </div>
                            @if($quote->discount_total > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">İndirim</span>
                                    <span
                                        class="font-medium text-danger-600">-{{ number_format($quote->discount_total, 2) }}
                                        {{ $quote->currency }}</span>
                                </div>
                            @endif
                            <div
                                class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-end">
                                <span class="text-xs font-bold text-gray-400 uppercase">Genel Toplam</span>
                                <span
                                    class="text-2xl font-black text-primary-600 dark:text-primary-400">{{ number_format($quote->grand_total, 2) }}
                                    {{ $quote->currency }}</span>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-3 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Bu teklif {{ $quote->valid_until ? $quote->valid_until->format('d.m.Y') : '-' }}
                                    tarihine kadar
                                    geçerlidir.</span>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-page-banner title="Fatura Detayı" subtitle="{{ $invoice->number }} - {{ $invoice->customer->name ?? '-' }}" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Invoice Info -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card>
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h3
                                    class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                    Fatura</h3>
                                <p class="text-gray-500 font-mono tracking-tight">{{ $invoice->number }}</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ \App\Models\Invoice::getStatusColor($invoice->status) }}">
                                    {{ \App\Models\Invoice::getStatusLabel($invoice->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-100 dark:border-gray-800">
                            <div>
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Fatura
                                    Adresi</h4>
                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $invoice->customer->name ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">
                                    {{ $invoice->customer->address ?? '-' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Fatura
                                    Detayları</h4>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-bold">Düzenleme:</span>
                                    {{ $invoice->issue_date ? $invoice->issue_date->format('d.m.Y') : now()->format('d.m.Y') }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <span class="font-bold">Vade:</span>
                                    {{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 uppercase tracking-tighter">
                                    <span class="font-bold">Para Birimi:</span> {{ $invoice->currency }}
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto mb-8">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">
                                        <th class="py-3">Hizmet / Açıklama</th>
                                        <th class="py-3 text-center">Miktar</th>
                                        <th class="py-3 text-right">Birim Fiyat</th>
                                        <th class="py-3 text-right">Toplam</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td class="py-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $item->description }}
                                                </div>
                                                @if($item->service)
                                                    <div class="text-xs text-gray-500 mt-0.5">Hizmet Kod:
                                                        {{ $item->service->identifier_code ?? '-' }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($item->qty, 0) }}
                                            </td>
                                            <td class="py-4 text-right text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="py-4 text-right text-sm font-bold text-gray-900 dark:text-white">
                                                {{ number_format($item->line_total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="w-full lg:w-1/2 ml-auto">
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>Ara Toplam</span>
                                    <span>{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>KDV Toplam</span>
                                    <span>{{ number_format($invoice->tax_total, 2) }} {{ $invoice->currency }}</span>
                                </div>
                                @if($invoice->discount_total > 0)
                                    <div class="flex justify-between text-sm text-danger-600 font-semibold">
                                        <span>İndirim</span>
                                        <span>-{{ number_format($invoice->discount_total, 2) }}
                                            {{ $invoice->currency }}</span>
                                    </div>
                                @endif
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                                    <span
                                        class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter">Genel
                                        Toplam</span>
                                    <span
                                        class="text-lg font-black text-primary-600">{{ number_format($invoice->grand_total, 2) }}
                                        {{ $invoice->currency }}</span>
                                </div>
                            </div>
                        </div>

                        @if($invoice->notes)
                            <div
                                class="mt-8 p-4 bg-gray-50 dark:bg-gray-800/30 rounded-xl border border-gray-100 dark:border-gray-700">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Notlar</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                                    {{ $invoice->notes }}
                                </p>
                            </div>
                        @endif
                    </x-card>
                </div>

                <!-- Actions & Payments -->
                <div class="space-y-6">
                    <x-card>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">İşlemler</h4>
                        <div class="space-y-3">
                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                                class="flex items-center justify-center w-full px-4 py-2 bg-gray-800 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                PDF İndir
                            </a>

                            @if($invoice->status === 'draft')
                                <form action="{{ route('invoices.send', $invoice) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-lg shadow-primary-500/30 transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Mail Gönder
                                    </button>
                                </form>
                            @elseif($invoice->status === 'sent' || $invoice->status === 'overdue')
                                <form action="{{ route('invoices.send', $invoice) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-gray-400 font-bold" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Maili Tekrar Gönder
                                    </button>
                                </form>
                            @endif

                            @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                                <a href="{{ route('invoices.edit', $invoice) }}"
                                    class="flex items-center justify-center w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Düzenle
                                </a>
                            @endif
                        </div>
                    </x-card>

                    <!-- Payment Box -->
                    <x-card class="bg-primary-50 dark:bg-primary-900/10 border-primary-100 dark:border-primary-900/20">
                        <h4 class="text-lg font-bold text-primary-800 dark:text-primary-400 mb-4">Tahsilat Takibi</h4>

                        <div class="space-y-4">
                            <div
                                class="bg-white dark:bg-gray-800/50 p-4 rounded-xl shadow-sm border border-primary-200 dark:border-primary-900">
                                <div class="text-xs font-bold text-gray-400 uppercase mb-1">Kalan Tutar</div>
                                <div class="text-2xl font-black text-primary-600 tracking-tighter">
                                    {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}
                                </div>
                            </div>

                            @if($invoice->remaining_amount > 0 && $invoice->status !== 'cancelled')
                                <form action="{{ route('invoices.payment', $invoice) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ödeme
                                            Girişi</label>
                                        <div class="flex space-x-2">
                                            <input type="number" name="amount" step="0.01" min="0.01"
                                                max="{{ $invoice->remaining_amount }}"
                                                value="{{ $invoice->remaining_amount }}" required
                                                class="w-full text-sm font-bold py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                            <button type="submit"
                                                class="px-4 py-2 bg-success-600 hover:bg-success-700 text-white font-bold rounded-lg shadow-lg shadow-success-500/30 transition-all">
                                                Kaydet
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div
                                    class="p-3 bg-success-100 dark:bg-success-900/20 text-success-700 dark:text-success-400 rounded-lg text-center font-bold text-sm">
                                    Fatura Tamamen Ödenmiştir.
                                </div>
                            @endif
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
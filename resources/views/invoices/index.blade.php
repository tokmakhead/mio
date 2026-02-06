<x-app-layout>
    <x-page-banner title="Muhasebe Yönetimi" subtitle="Faturalarınızı ve tahsilatlarınızı bu ekrandan takip edebilirsiniz.">
        <x-slot name="metric">
            <div class="bg-white/10 backdrop-blur-md rounded-lg px-4 py-2 border border-white/20">
                <span class="text-xs text-white/70 uppercase font-bold tracking-wider">Bekleyen Tutar</span>
                <div class="text-xl font-black text-white">
                    {{ number_format($totalPending, 2) }} ₺
                </div>
            </div>
        </x-slot>
    </x-page-banner>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Fatura" value="{{ $totalInvoices }}" icon="document-text" color="blue" />
                <x-kpi-card title="Fatura Tutarı" value="{{ number_format($totalAmount, 2) }} ₺" icon="banknotes" color="indigo" />
                <x-kpi-card title="Toplam Tahsilat" value="{{ number_format($totalCollected, 2) }} ₺" icon="check-circle" color="green" />
                <x-kpi-card title="Bekleyen" value="{{ number_format($totalPending, 2) }} ₺" icon="clock" color="amber" />
            </div>

            <x-card>
                <!-- Filters & Search -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Fatura no veya müşteri..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <select name="status" onchange="this.form.submit()"
                            class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Tüm Durumlar</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Gönderildi</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Ödendi</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Vadesi Geçmiş</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                        </select>

                        @if(request()->anyFilled(['search', 'status']))
                            <a href="{{ route('invoices.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-primary-600 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Temizle
                            </a>
                        @endif
                    </form>

                    <div class="flex space-x-3">
                        <a href="{{ route('invoices.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-lg shadow-primary-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Yeni Fatura
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Fatura No</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Müşteri</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kalan</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Vade</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white">
                                        {{ $invoice->number }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $invoice->customer->name }}</div>
                                        <div class="text-xs text-gray-500 tracking-tight">{{ $invoice->customer->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ \App\Models\Invoice::getStatusColor($invoice->status) }}">
                                            {{ \App\Models\Invoice::getStatusLabel($invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($invoice->grand_total, 2) }} {{ $invoice->currency }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold {{ $invoice->remaining_amount > 0 ? 'text-danger-600' : 'text-success-600' }}">
                                            {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm {{ $invoice->due_date < now() && $invoice->remaining_amount > 0 ? 'text-danger-600 font-bold' : 'text-gray-600 dark:text-gray-400' }}">
                                            {{ $invoice->due_date->format('d.m.Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('invoices.show', $invoice) }}" 
                                           class="inline-flex items-center p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors"
                                           title="Detay">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                                           class="inline-flex items-center p-2 text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                                           title="PDF">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <x-empty-state 
                                            title="Fatura Bulunamadı" 
                                            message="Henüz bir fatura oluşturulmamış veya arama kriterlerine uygun sonuç yok."
                                            cta="Yeni Fatura Oluştur"
                                            link="{{ route('invoices.create') }}"
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $invoices->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
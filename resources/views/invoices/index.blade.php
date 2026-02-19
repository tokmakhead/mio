<x-app-layout>
    <x-page-banner title="Finans Merkezi"
        subtitle="Faturalarınızı ve tahsilatlarınızı bu ekrandan takip edebilirsiniz.">
        <x-slot name="metric">
            @php $financeService = new \App\Services\FinanceService(); @endphp
            <div class="text-right">
                <span class="block text-xs text-white/70 uppercase font-bold tracking-wider">Bekleyen Tahsilat
                    ({{ $defaultCurrency }})</span>
                <div class="text-xl font-black text-white">
                    {{ $financeService->formatCurrency($totalPending, $defaultCurrency) }}
                </div>
            </div>
        </x-slot>
    </x-page-banner>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Fatura" value="{{ $totalInvoices }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' />

                <x-kpi-card title="Fatura Tutarı ({{ $defaultCurrency }})"
                    value="{{ $financeService->formatCurrency($totalAmount, $defaultCurrency) }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />

                <x-kpi-card title="Tahsilat ({{ $defaultCurrency }})"
                    value="{{ $financeService->formatCurrency($totalCollected, $defaultCurrency) }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />

                <x-kpi-card title="Bekleyen ({{ $defaultCurrency }})"
                    value="{{ $financeService->formatCurrency($totalPending, $defaultCurrency) }}" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            @if(count($summary) > 1)
                <!-- Multi-Currency Breakdown -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @foreach($summary as $curr => $data)
                        @if($curr != $defaultCurrency)
                            <div
                                class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">{{ $curr }}
                                        Özeti</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500">Tahsilat:
                                        {{ $financeService->formatCurrency($data['receivable'], $curr) }}</span>
                                    <span class="text-danger-600 font-bold">Bekleyen:
                                        {{ $financeService->formatCurrency($data['payable'], $curr) }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <x-card>
                <!-- Filters & Search -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <form action="{{ route('invoices.index') }}" method="GET"
                        class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Fatura no veya müşteri..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <select name="status" onchange="this.form.submit()"
                            class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Tüm Durumlar</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Gönderildi</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Ödendi</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Vadesi Geçmiş
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal
                                Edildi</option>
                        </select>

                        @if(request()->anyFilled(['search', 'status']))
                            <a href="{{ route('invoices.index') }}"
                                class="inline-flex items-center text-sm text-gray-500 hover:text-primary-600 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Temizle
                            </a>
                        @endif
                    </form>

                    <div class="flex space-x-3">
                        <a href="{{ route('accounting.reconciliation.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-900 dark:bg-gray-800 hover:bg-gray-800 dark:hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg shadow-gray-500/20 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mutabakat
                        </a>
                        <a href="{{ route('invoices.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-lg shadow-primary-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Yeni Fatura
                        </a>
                    </div>
                </div>

                <form id="bulkForm" method="POST" action="{{ route('invoices.bulk_export') }}">
                    @csrf
                    <!-- Bulk Actions Toolbar (Hidden by default, shown when items selected) -->
                    <div id="bulkActions"
                        class="hidden mb-4 p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg flex items-center justify-between">
                        <span class="text-sm text-primary-700 dark:text-primary-300 font-medium px-2">
                            <span id="selectedCount">0</span> kayıt seçildi
                        </span>
                        <button type="submit"
                            class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Seçilenleri İndir (ZIP)
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr
                                    class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-6 py-4 w-4">
                                        <input type="checkbox" id="selectAll"
                                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Fatura No
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Müşteri
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Durum
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tutar
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kalan
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Vade
                                    </th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="ids[]" value="{{ $invoice->id }}"
                                                class="row-checkbox rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                        </td>
                                        <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white">
                                            {{ $invoice->number }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $invoice->customer->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 tracking-tight">
                                                {{ $invoice->customer->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ \App\Models\Invoice::getStatusColor($invoice->status) }}">
                                                {{ \App\Models\Invoice::getStatusLabel($invoice->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ number_format($invoice->grand_total, 2) }} {{ $invoice->currency }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="text-sm font-semibold {{ $invoice->remaining_amount > 0 ? 'text-danger-600' : 'text-success-600' }}">
                                                {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="text-sm {{ $invoice->due_date < now() && $invoice->remaining_amount > 0 ? 'text-danger-600 font-bold' : 'text-gray-600 dark:text-gray-400' }}">
                                                {{ $invoice->due_date->format('d.m.Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50/50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-indigo-100 dark:hover:bg-indigo-900/40"
                                                    title="Detay">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('invoices.edit', $invoice) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50/50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-emerald-100 dark:hover:bg-emerald-900/40"
                                                    title="Düzenle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-amber-50/50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-amber-100 dark:hover:bg-amber-900/40"
                                                    title="PDF">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <x-empty-state title="Fatura Kaydı Bulunmuyor"
                                                message="Sistemde henüz kayıtlı fatura bulunmamaktadır. Yeni bir fatura ekleyerek başlayabilirsiniz."
                                                cta="Yeni Fatura Ekle" link="{{ route('invoices.create') }}" />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="mt-6">
                    {{ $invoices->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
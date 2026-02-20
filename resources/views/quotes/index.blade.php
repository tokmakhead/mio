<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Teklif Havuzu" subtitle="Müşterilerinize sunduğunuz teklifleri buradan oluşturun ve yönetin."
        metric="{{ $totalQuotes }} Toplam Teklif" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid (Counts) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                <x-kpi-card title="Toplam Teklif (Adet)" value="{{ $totalQuotes }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' />
                <x-kpi-card title="Taslak (Adet)" value="{{ $draftCount }}" tone="info"
                    icon='<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>' />
                <x-kpi-card title="Gönderildi (Adet)" value="{{ $sentCount }}" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>' />
                <x-kpi-card title="Kabul Edildi (Adet)" value="{{ $acceptedCount }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            <!-- Financial KPIs (Currency Based) -->
            @php $financeService = new \App\Services\FinanceService(); @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @foreach($financials as $curr => $data)
                    @php
                        $pending = ($data['draft'] ?? 0) + ($data['sent'] ?? 0);
                        $won = $data['accepted'] ?? 0;
                        $total = $data['total'] ?? 0;
                    @endphp
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $curr }} Finansal Durum
                                </h3>
                                <div class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                                    {{ $financeService->formatCurrency($total, $curr) }}
                                </div>
                            </div>
                            <span
                                class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300 font-bold text-xs">
                                {{ $curr }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-warning-500 mr-2"></span>
                                    Açık (Potansiyel)
                                </span>
                                <span
                                    class="font-semibold text-gray-700 dark:text-gray-200">{{ $financeService->formatCurrency($pending, $curr) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-success-500 mr-2"></span>
                                    Kazanılan
                                </span>
                                <span
                                    class="font-semibold text-gray-700 dark:text-gray-200">{{ $financeService->formatCurrency($won, $curr) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Filters & Actions -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 space-y-4 md:space-y-0">
                <form action="{{ route('quotes.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Teklif no veya müşteri..."
                            class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500 w-64">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <select name="status" onchange="this.form.submit()"
                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Tüm Durumlar</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Gönderildi</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Kabul Edildi
                        </option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Süresi Dolmuş
                        </option>
                    </select>

                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('quotes.index') }}" class="text-sm text-danger-600 hover:underline">Filtreleri
                            Temizle</a>
                    @endif
                </form>

                <a href="{{ route('quotes.create') }}"
                    class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    <span>Yeni Teklif</span>
                </a>
            </div>




            <!-- Table -->
            <x-card class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">

                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Teklif
                                    No
                                </th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Müşteri
                                </th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Tutar
                                </th>
                                <th
                                    class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">
                                    Durum</th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Geçerlilik
                                </th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300 text-right">
                                    İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($quotes as $quote)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">

                                    <td class="px-4 py-4">
                                        <span
                                            class="font-mono text-sm font-bold text-gray-900 dark:text-white">{{ $quote->number }}</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($quote->customer)
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $quote->customer->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $quote->customer->email }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($quote->grand_total, 2) }} {{ $quote->currency }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\Quote::getStatusColor($quote->status) }}">
                                            {{ \App\Models\Quote::getStatusLabel($quote->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $quote->valid_until ? $quote->valid_until->format('d.m.Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('quotes.show', $quote) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50/50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-indigo-100 dark:hover:bg-indigo-900/40"
                                                title="Görüntüle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('quotes.edit', $quote) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50/50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-emerald-100 dark:hover:bg-emerald-900/40"
                                                title="Düzenle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('quotes.pdf', $quote) }}" target="_blank"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-amber-50/50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-amber-100 dark:hover:bg-amber-900/40"
                                                title="PDF İndir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Teklif Kaydı
                                                Bulunmuyor
                                            </h3>
                                            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mt-1">
                                                Sistemde henüz kayıtlı teklif bulunmamaktadır. Yeni bir teklif
                                                oluşturarak
                                                başlayabilirsiniz.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($quotes->hasPages())
                    <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $quotes->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Teklif Yönetimi" subtitle="Müşterilerinize özel teklifler oluşturun ve yönetin."
        metric="{{ $totalQuotes }} Toplam Teklif" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Teklif" value="{{ $totalQuotes }}" tone="primary" />
                <x-kpi-card title="Taslak" value="{{ $draftCount }}" tone="info" />
                <x-kpi-card title="Gönderildi" value="{{ $sentCount }}" tone="warning" />
                <x-kpi-card title="Kabul Edildi" value="{{ $acceptedCount }}" tone="success" />
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
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
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Teklif No
                                </th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Müşteri
                                </th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Tutar</th>
                                <th
                                    class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">
                                    Durum</th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Geçerlilik
                                </th>
                                <th class="px-4 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300 text-right">
                                    İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($quotes as $quote)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="font-mono text-sm font-bold text-gray-900 dark:text-white">{{ $quote->number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $quote->customer->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $quote->customer->email }}
                                        </div>
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
                                        {{ $quote->valid_until->format('d.m.Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('quotes.show', $quote) }}"
                                                class="p-1 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded transition-colors"
                                                title="Görüntüle">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('quotes.pdf', $quote) }}"
                                                class="p-1 text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/20 rounded transition-colors"
                                                title="PDF İndir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 17h6m-6-4h6m-6-4h6"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Henüz teklif yok
                                            </h3>
                                            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mt-1">
                                                Müşterilerinize ilk teklifinizi oluşturarak başlayın.</p>
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
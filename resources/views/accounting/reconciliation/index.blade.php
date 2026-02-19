<x-app-layout>
    <x-page-banner title="Muhasebe Mutabakatı"
        subtitle="Fatura tutarsızlıklarını ve müşteri bakiyelerini buradan yönetebilirsiniz.">
        <x-slot name="actions">
            <form action="{{ route('accounting.reconciliation.fix') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-lg shadow-primary-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Durumları Güncelle
                </button>
            </form>
        </x-slot>
    </x-page-banner>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-kpi-card title="Hatalı Faturalar" value="{{ $errors->count() }}" tone="danger"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>' />

                <x-kpi-card title="Tutarlı Faturalar" value="{{ $consistent->count() }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />

                <x-kpi-card title="Toplam Müşteri" value="{{ $customers->count() }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />

                <x-kpi-card title="Toplam Bakiye" value="{{ number_format($customers->sum('balance'), 2) }} ₺"
                    tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>' />
            </div>

            <!-- Filters -->
            <x-card>
                <form method="GET" action="{{ route('accounting.reconciliation.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arama</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Müşteri veya Fatura No..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="start_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Başlangıç</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label for="end_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bitiş</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Balance Status -->
                    <div>
                        <label for="balance_status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bakiye
                            Durumu</label>
                        <div class="flex items-center space-x-2">
                            <select name="balance_status" id="balance_status"
                                class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Tümü</option>
                                <option value="debit" {{ request('balance_status') == 'debit' ? 'selected' : '' }}>Borçlu
                                    (Debit)</option>
                                <option value="credit" {{ request('balance_status') == 'credit' ? 'selected' : '' }}>
                                    Alacaklı (Credit)</option>
                                <option value="balanced" {{ request('balance_status') == 'balanced' ? 'selected' : '' }}>
                                    Dengede</option>
                            </select>
                            <button type="submit"
                                class="px-3 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            @if(request()->anyFilled(['search', 'start_date', 'end_date', 'balance_status']))
                                <a href="{{ route('accounting.reconciliation.index') }}"
                                    class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Errors Table -->
            @if($errors->count() > 0)
                <x-card class="border-l-4 border-red-500">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <span class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </span>
                            Müdahale Gereken Faturalar
                        </h3>
                        <span
                            class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">{{ $errors->count() }}
                            Sorun</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Fatura No
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Müşteri
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Durum
                                    </th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        Beklenen</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        Gerçekleşen</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                        Fark</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($errors as $invoice)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white">
                                            <a href="{{ route('invoices.show', $invoice) }}"
                                                class="hover:underline text-primary-600 hover:text-primary-700">
                                                {{ $invoice->number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                            @if($invoice->customer)
                                                {{ $invoice->customer->name }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-600 dark:text-gray-400 font-mono">
                                            {{ number_format($invoice->grand_total, 2) }} {{ $invoice->currency }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-600 dark:text-gray-400 font-mono">
                                            {{ number_format($invoice->paid_amount, 2) }} {{ $invoice->currency }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right font-bold text-red-600 font-mono">
                                            {{ number_format($invoice->grand_total - $invoice->paid_amount, 2) }}
                                            {{ $invoice->currency }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            @endif

            <!-- Customer Balances -->
            <x-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        <span
                            class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400 p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </span>
                        Müşteri Bakiye Özeti
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Müşteri
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Toplam Borç (Debit)</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Toplam Alacak (Credit)</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Net Bakiye</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">
                                    İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($customers as $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        @if($data['customer'])
                                            {{ $data['customer']->name }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-red-600 font-mono">
                                        {{ number_format($data['debit'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-green-600 font-mono">
                                        {{ number_format($data['credit'], 2) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-right font-bold {{ $data['balance'] > 0 ? 'text-red-600' : 'text-green-600' }} font-mono">
                                        {{ number_format(abs($data['balance']), 2) }}
                                        <span
                                            class="text-xs font-normal ml-1">{{ $data['balance'] > 0 ? '(B)' : '(A)' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($data['customer'])
                                            <a href="{{ route('customers.ledger', $data['customer']) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50/50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-indigo-100 dark:hover:bg-indigo-900/40"
                                                title="Ekstre">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>
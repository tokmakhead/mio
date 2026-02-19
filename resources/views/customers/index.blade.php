<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Cari Yönetimi"
        subtitle="Müşteri portföyünüzü ve cari hesap detaylarını buradan yönetebilirsiniz."
        metric="{{ $totalCustomers }} Müşteri" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Cari" value="{{ $totalCustomers }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />

                @php $summaryCount = 0; @endphp
                @foreach($globalSummary as $currency => $amounts)
                    @if($amounts['receivable'] > 0 || $amounts['payable'] > 0)
                        @php $summaryCount++; @endphp
                        @if($summaryCount <= 3) {{-- Limit to 3 more cards to keep 4-grid --}}
                            <x-kpi-card title="Alacak ({{ $currency }})"
                                value="{{ number_format($amounts['receivable'], 2, ',', '.') }}{{ $currency == 'TRY' ? '₺' : ' ' . $currency }}"
                                tone="warning"
                                icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
                        @endif
                    @endif
                @endforeach

                @if($summaryCount == 0)
                    <x-kpi-card title="Toplam Alacak" value="0,00 ₺" tone="warning"
                        icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
                @endif

                @if($summaryCount < 3)
                    <x-kpi-card title="Aktif Müşteri" value="{{ $activeCustomers }}" tone="success"
                        icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
                @endif
            </div>

            <!-- Filter Card -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('customers.index') }}"
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Search -->
                    <div class="md:col-span-3">
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arama</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Ad, email..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Durum</label>
                        <select name="status" id="status"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Tümü</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif
                            </option>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="md:col-span-2">
                        <label for="type"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tür</label>
                        <select name="type" id="type"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Tümü</option>
                            <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>Bireysel
                            </option>
                            <option value="corporate" {{ request('type') == 'corporate' ? 'selected' : '' }}>Kurumsal
                            </option>
                        </select>
                    </div>

                    <!-- Balance Status -->
                    <div class="md:col-span-3">
                        <label for="balance_status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Bakiye</label>
                        <select name="balance_status" id="balance_status"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Tümü</option>
                            <option value="debit" {{ request('balance_status') == 'debit' ? 'selected' : '' }}>Borçlu
                            </option>
                            <option value="balanced" {{ request('balance_status') == 'balanced' ? 'selected' : '' }}>
                                Dengede</option>
                            <option value="credit" {{ request('balance_status') == 'credit' ? 'selected' : '' }}>Alacaklı
                            </option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="md:col-span-2 flex space-x-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 shadow-sm font-medium">
                            Sorgula
                        </button>
                        <a href="{{ route('customers.index') }}"
                            class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200 text-center font-medium">
                            Sıfırla
                        </a>
                    </div>
                </form>
            </x-card>

            <!-- Customers Table or Empty State -->
            @if($customers->count() > 0)
                <x-card>
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Müşteriler</h3>
                        <a href="{{ route('customers.create') }}"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            <span>Yeni Müşteri</span>
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Müşteri</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tür</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        İletişim</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Durum</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Bakiye</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Hizmet</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($customers as $customer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                        <!-- Customer -->
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center text-white font-semibold">
                                                    {{ substr($customer->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $customer->name }}
                                                    </div>
                                                    @if($customer->city)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->city }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Type -->
                                        <td class="px-4 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full {{ $customer->type == 'individual' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' }}">
                                                {{ $customer->type == 'individual' ? 'Bireysel' : 'Kurumsal' }}
                                            </span>
                                        </td>

                                        <!-- Contact -->
                                        <td class="px-4 py-4">
                                            <div class="text-sm">
                                                @if($customer->email)
                                                    <div class="text-gray-900 dark:text-white">{{ $customer->email }}</div>
                                                @endif
                                                @if($customer->phone)
                                                    <div class="text-gray-500 dark:text-gray-400">{{ $customer->phone }}</div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2">
                                                <div
                                                    class="w-2 h-2 rounded-full {{ $customer->status == 'active' ? 'bg-success-500' : 'bg-gray-400' }}">
                                                </div>
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-300">{{ $customer->status == 'active' ? 'Aktif' : 'Pasif' }}</span>
                                            </div>
                                        </td>

                                        <!-- Balance -->
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex flex-col items-end space-y-1">
                                                @forelse($customer->balances as $currency => $balance)
                                                    @if($balance != 0)
                                                        <span
                                                            class="text-xs font-mono font-bold {{ $balance > 0 ? 'text-danger-600' : 'text-blue-600' }}">
                                                            {{ number_format(abs($balance), 2) }}
                                                            {{ $currency == 'TRY' ? '₺' : $currency }}
                                                            <span class="text-[9px] uppercase opacity-75">
                                                                {{ $balance > 0 ? 'BORÇ' : 'ALACAK' }}
                                                            </span>
                                                        </span>
                                                    @endif
                                                @empty
                                                    <span class="text-xs font-mono font-bold text-success-600">0.00 ₺</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <!-- Services Count -->
                                        <td class="px-4 py-4">
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $customer->services_count ?? 0 }}</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('customers.ledger', $customer) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-50/50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-blue-100 dark:hover:bg-blue-900/40"
                                                    title="Ekstre Görüntüle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('customers.show', $customer) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50/50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-indigo-100 dark:hover:bg-indigo-900/40"
                                                    title="Görüntüle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('customers.edit', $customer) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50/50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-emerald-100 dark:hover:bg-emerald-900/40"
                                                    title="Düzenle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>
                                                <x-delete-modal title="Müşteriyi Sil"
                                                    message="Bu müşteriyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                                    :action="route('customers.destroy', $customer)" title="Sil" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </x-card>
            @else
                <x-card>
                    <x-empty-state title="Cari Kayıt Bulunmuyor"
                        message="Sistemde henüz kayıtlı cari hesap bulunmamaktadır. Yeni bir cari ekleyerek başlayabilirsiniz."
                        cta="Yeni Cari Ekle" href="{{ route('customers.create') }}" />
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Müşteri Yönetimi" subtitle="Tüm müşterilerinizi buradan yönetebilirsiniz"
        metric="{{ $totalCustomers }} Müşteri" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Müşteri" value="{{ $totalCustomers }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' />

                <x-kpi-card title="Aktif Müşteri" value="{{ $activeCustomers }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />

                <x-kpi-card title="Toplam Alacak" value="{{ number_format($totalReceivable, 2) }}₺" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />

                <x-kpi-card title="Toplam Borç" value="{{ number_format($totalPayable, 2) }}₺" tone="danger"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            <!-- Filter Card -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('customers.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arama</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Ad, email..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Status -->
                    <div>
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
                    <div>
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

                    <!-- Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200">
                            Filtrele
                        </button>
                        <a href="{{ route('customers.index') }}"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                            Temizle
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

                                        <!-- Services Count -->
                                        <td class="px-4 py-4">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">0</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('customers.show', $customer) }}"
                                                    class="p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors"
                                                    title="Görüntüle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('customers.edit', $customer) }}"
                                                    class="p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors"
                                                    title="Düzenle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <x-delete-modal title="Müşteriyi Sil"
                                                    message="Bu müşteriyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                                    :action="route('customers.destroy', $customer)" title="Sil">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </x-delete-modal>
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
                    <x-empty-state title="Henüz müşteri yok"
                        message="İlk müşterinizi ekleyerek başlayın. Müşteri ekledikten sonra hizmet, fatura ve ödeme takibi yapabilirsiniz."
                        cta="Yeni Müşteri Ekle" href="{{ route('customers.create') }}" />
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
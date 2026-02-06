<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Hizmet Yönetimi" subtitle="Tüm hizmetlerinizi buradan yönetebilirsiniz"
        metric="{{ number_format($mrr, 2) }}₺ MRR" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Hizmet" value="{{ $totalServices }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' />

                <x-kpi-card title="Domain Sayısı" value="{{ $domainCount }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>' />

                <x-kpi-card title="Hosting Sayısı" value="{{ $hostingCount }}" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>' />

                <x-kpi-card title="Aylık Gelir (MRR)" value="{{ number_format($mrr, 2) }}₺" tone="danger"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            <!-- Filter Card -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('services.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arama</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Ad, kod..."
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
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Askıda
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal
                            </option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Süresi Dolmuş
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
                            <option value="hosting" {{ request('type') == 'hosting' ? 'selected' : '' }}>Hosting</option>
                            <option value="domain" {{ request('type') == 'domain' ? 'selected' : '' }}>Domain</option>
                            <option value="ssl" {{ request('type') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>E-posta</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Diğer</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200">
                            Filtrele
                        </button>
                        <a href="{{ route('services.index') }}"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                            Temizle
                        </a>
                    </div>
                </form>
            </x-card>

            <!-- Services Table or Empty State -->
            @if($services->count() > 0)
                <x-card>
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Hizmetler</h3>
                        <a href="{{ route('services.create') }}"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            <span>Yeni Hizmet</span>
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Hizmet</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Kod</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tür</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Müşteri</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Sağlayıcı</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Dönem</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Durum</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Vade</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($services as $service)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                        <!-- Service Name + Price -->
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $service->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($service->price, 2) }} {{ $service->currency }}</div>
                                        </td>

                                        <!-- Identifier Code Pill -->
                                        <td class="px-4 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-mono font-medium rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                {{ $service->identifier_code }}
                                            </span>
                                        </td>

                                        <!-- Type Badge -->
                                        <td class="px-4 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\Service::getTypeBadgeColor($service->type) }}">
                                                {{ \App\Models\Service::getTypeLabel($service->type) }}
                                            </span>
                                        </td>

                                        <!-- Customer -->
                                        <td class="px-4 py-4">
                                            <a href="{{ route('customers.show', $service->customer) }}"
                                                class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                {{ $service->customer->name }}
                                            </a>
                                        </td>

                                        <!-- Provider -->
                                        <td class="px-4 py-4">
                                            <a href="{{ route('providers.show', $service->provider) }}"
                                                class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                {{ $service->provider->name }}
                                            </a>
                                        </td>

                                        <!-- Cycle -->
                                        <td class="px-4 py-4">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ \App\Models\Service::getCycleLabel($service->cycle) }}
                                            </span>
                                        </td>

                                        <!-- Status Dot -->
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="w-2 h-2 rounded-full {{ \App\Models\Service::getStatusDotColor($service->status) }}"></span>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ \App\Models\Service::getStatusLabel($service->status) }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Expiry Date with Color -->
                                        <td class="px-4 py-4">
                                            <div class="text-sm {{ $service->expiry_color }}">
                                                {{ $service->end_date->format('d.m.Y') }}
                                                @if($service->days_until_expiry >= 0)
                                                    <div class="text-xs">({{ $service->days_until_expiry }} gün)</div>
                                                @else
                                                    <div class="text-xs">(Dolmuş)</div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('services.show', $service) }}"
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
                                                <a href="{{ route('services.edit', $service) }}"
                                                    class="p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors"
                                                    title="Düzenle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <x-delete-modal title="Hizmeti Sil"
                                                    message="Bu hizmeti silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                                    :action="route('services.destroy', $service)" title="Sil">
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
                        {{ $services->links() }}
                    </div>
                </x-card>
            @else
                <x-card>
                    <x-empty-state title="Henüz hizmet yok"
                        message="İlk hizmetinizi ekleyerek başlayın. Hizmetler, müşterilerinize sunduğunuz hosting, domain, SSL gibi ürünlerdir."
                        cta="Yeni Hizmet Ekle" href="{{ route('services.create') }}" />
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
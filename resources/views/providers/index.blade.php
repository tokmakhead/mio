<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Sağlayıcı Yönetimi" subtitle="Tüm sağlayıcılarınızı buradan yönetebilirsiniz"
        metric="{{ $totalProviders }} Sağlayıcı" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Sağlayıcı" value="{{ $totalProviders }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>' />

                <x-kpi-card title="Tür Çeşidi" value="{{ $uniqueTypes }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>' />

                <x-kpi-card title="Web Sitesi Olan" value="{{ $withWebsite }}" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>' />

                <x-kpi-card title="Aktif Hizmet" value="{{ $activeServices }}" tone="danger"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>' />
            </div>

            <!-- Filter Card -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('providers.index') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arama</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Ad, email..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end space-x-2 md:col-span-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200">
                            Filtrele
                        </button>
                        <a href="{{ route('providers.index') }}"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                            Temizle
                        </a>
                    </div>
                </form>
            </x-card>

            <!-- Providers Table or Empty State -->
            @if($providers->count() > 0)
                <x-card>
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sağlayıcılar</h3>
                        <a href="{{ route('providers.create') }}"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            <span>Yeni Sağlayıcı</span>
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Sağlayıcı</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Türler</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        İletişim</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Hizmet</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($providers as $provider)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                        <!-- Provider -->
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $provider->name }}</div>
                                            @if($provider->website)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $provider->website }}</div>
                                            @endif
                                        </td>

                                        <!-- Types -->
                                        <td class="px-4 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($provider->types as $type)
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\Provider::getTypeBadgeColor($type) }}">
                                                        {{ \App\Models\Provider::getTypeLabel($type) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>

                                        <!-- Contact -->
                                        <td class="px-4 py-4">
                                            <div class="text-sm">
                                                @if($provider->email)
                                                    <div class="text-gray-900 dark:text-white">{{ $provider->email }}</div>
                                                @endif
                                                @if($provider->phone)
                                                    <div class="text-gray-500 dark:text-gray-400">{{ $provider->phone }}</div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Services Count -->
                                        <td class="px-4 py-4">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">0</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('providers.show', $provider) }}"
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
                                                <a href="{{ route('providers.edit', $provider) }}"
                                                    class="p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors"
                                                    title="Düzenle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <x-delete-modal title="Sağlayıcıyı Sil"
                                                    message="Bu sağlayıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                                    :action="route('providers.destroy', $provider)" title="Sil">
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
                        {{ $providers->links() }}
                    </div>
                </x-card>
            @else
                <x-card>
                    <x-empty-state title="Henüz sağlayıcı yok"
                        message="İlk sağlayıcınızı ekleyerek başlayın. Sağlayıcılar, hizmetlerinizi sunduğunuz firmalardır."
                        cta="Yeni Sağlayıcı Ekle" href="{{ route('providers.create') }}" />
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
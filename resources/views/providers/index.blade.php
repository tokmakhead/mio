<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Tedarikçiler"
        subtitle="Hizmet sağlayıcılarınızı ve iş ortaklarınızı buradan yönetebilirsiniz."
        metric="{{ $totalProviders }} Sağlayıcı" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Sağlayıcı" value="{{ $totalProviders }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>' />

                <x-kpi-card title="Tür Çeşidi" value="{{ $uniqueTypes }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>' />

                <x-kpi-card title="Toplam Maliyet" value="{{ number_format($totalCosts['TRY'] ?? 0, 0) }} ₺"
                    tone="danger"
                    sub-value="{{ $totalCosts->except('TRY')->map(fn($v, $curr) => number_format($v, 0) . ' ' . $curr)->join(' ') }}"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            <!-- Filter Card -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('providers.index') }}" x-data="{
                        submit() { this.$refs.form.submit() }
                    }" x-ref="form" class="relative">
                    <!-- Search with Icon -->
                    <div class="relative">
                        <label for="search" class="sr-only">Arama</label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Sağlayıcı adı veya e-posta ile ara..." @input.debounce.500ms="submit()"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out">
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
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $provider->services_count }}</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('providers.show', $provider) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50/50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-indigo-100 dark:hover:bg-indigo-900/40"
                                                    title="Görüntüle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('providers.edit', $provider) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50/50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-emerald-100 dark:hover:bg-emerald-900/40"
                                                    title="Düzenle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>
                                                <x-delete-modal title="Sağlayıcıyı Sil"
                                                    message="Bu sağlayıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                                    :action="route('providers.destroy', $provider)" button-title="Sil">
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
                    <x-empty-state title="Tedarikçi Kaydı Bulunmuyor"
                        message="Sistemde henüz kayıtlı tedarikçi bulunmamaktadır. Yeni bir tedarikçi ekleyerek başlayabilirsiniz."
                        cta="Yeni Tedarikçi Ekle" href="{{ route('providers.create') }}" />
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
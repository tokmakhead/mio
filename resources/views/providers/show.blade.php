<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="{{ $provider->name }}" subtitle="Sağlayıcı Detayları" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mb-6">
                <a href="{{ route('providers.edit', $provider) }}"
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Düzenle</span>
                </a>

                <x-delete-modal title="Sağlayıcıyı Sil"
                    message="Bu sağlayıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                    :action="route('providers.destroy', $provider)"
                    class="px-4 py-2 bg-danger-600 hover:bg-danger-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    <span>Sil</span>
                </x-delete-modal>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Provider Information Card -->
                <div class="lg:col-span-1">
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sağlayıcı Bilgileri</h3>

                        <!-- Info List -->
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ad</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $provider->name }}</p>
                            </div>

                            <!-- Corporate Info -->
                            @if($provider->tax_office || $provider->tax_number)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fatura
                                        Bilgileri</label>
                                    <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                        @if($provider->tax_office)
                                            <div><span class="text-gray-500">V.D.:</span> {{ $provider->tax_office }}</div>
                                        @endif
                                        @if($provider->tax_number)
                                            <div><span class="text-gray-500">V.No:</span> {{ $provider->tax_number }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($provider->address)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Adres</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                        {{ $provider->address }}</p>
                                </div>
                            @endif

                            <!-- Types -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hizmet
                                    Türleri</label>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($provider->types as $type)
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\Provider::getTypeBadgeColor($type) }}">
                                            {{ \App\Models\Provider::getTypeLabel($type) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Website -->
                            @if($provider->website)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Website</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $provider->website }}</p>
                                </div>
                            @endif

                            <!-- Email -->
                            @if($provider->email)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">E-posta</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $provider->email }}</p>
                                </div>
                            @endif

                            <!-- Phone -->
                            @if($provider->phone)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telefon</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $provider->phone }}</p>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($provider->notes)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notlar</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $provider->notes }}</p>
                                </div>
                            @endif

                            <!-- Created At -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kayıt
                                    Tarihi</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $provider->created_at ? $provider->created_at->format('d.m.Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Services -->
                <div class="lg:col-span-2">
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hizmetler</h3>
                        @if($provider->services->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr
                                            class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Hizmet</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Müşteri</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Durum</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Vade</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-right">Maliyet</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-right">Net Kâr</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-right">Tutar</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Panel</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($provider->services as $service)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $service->name }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                    @if($service->customer)
                                                        <a href="{{ route('customers.show', $service->customer) }}"
                                                            class="hover:text-primary-600">
                                                            {{ $service->customer->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span
                                                            class="w-2 h-2 rounded-full {{ \App\Models\Service::getStatusDotColor($service->status) }}"></span>
                                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                                            {{ \App\Models\Service::getStatusLabel($service->status) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $service->end_date ? $service->end_date->format('d.m.Y') : '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-right">
                                                    {{ number_format($service->buying_price, 2) }} {{ $service->currency }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-bold text-right {{ ($service->price - $service->buying_price) > 0 ? 'text-success-600' : 'text-danger-600' }}">
                                                     {{ number_format($service->price - $service->buying_price, 2) }} {{ $service->currency }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white text-right">
                                                    {{ number_format($service->price, 2) }} {{ $service->currency }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($provider->website)
                                                        <a href="{{ $provider->website }}" target="_blank" class="text-gray-400 hover:text-primary-600 transition-colors" title="Panel'e Git">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                        </a>
                                                    @else
                                                        <span class="text-gray-300">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <x-empty-state title="Henüz hizmet yok"
                                message="Bu sağlayıcıya ait henüz bir hizmet kaydı bulunmuyor." cta="Hizmet Ekle"
                                href="{{ route('services.create') }}" />
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
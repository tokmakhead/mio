<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="{{ $service->name }}" subtitle="Hizmet Detayları ({{ $service->identifier_code }})" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mb-6">
                <a href="{{ route('services.edit', $service) }}"
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Düzenle</span>
                </a>

                <x-delete-modal title="Hizmeti Sil"
                    message="Bu hizmeti silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                    :action="route('services.destroy', $service)"
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
                <!-- Service Information Card -->
                <div class="lg:col-span-1">
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hizmet Bilgileri</h3>

                        <!-- Info List -->
                        <div class="space-y-4">
                            <!-- Name & Code -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hizmet Adı
                                    / Kod</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $service->name }}
                                    ({{ $service->identifier_code }})</p>
                            </div>

                            <!-- Type -->
                            <div>
                                <label
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tür</label>
                                <div class="mt-2">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\Service::getTypeBadgeColor($service->type) }}">
                                        {{ \App\Models\Service::getTypeLabel($service->type) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Price & Cycle -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Satış
                                    Fiyatı / Dönem</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ number_format($service->price, 2) }} {{ $service->currency }} /
                                    {{ \App\Models\Service::getCycleLabel($service->cycle) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">MRR: {{ number_format($service->mrr, 2) }} ₺</p>
                            </div>

                            <!-- Cost & Profit -->
                            @if($service->buying_price)
                                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Maliyet
                                        & Kâr</label>
                                    <div class="mt-1 space-y-1">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Alış: {{ number_format($service->buying_price, 2) }} {{ $service->currency }}
                                        </p>
                                        <div class="flex items-center space-x-2">
                                            <p
                                                class="text-sm font-semibold {{ $service->profit >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                                                Kâr: {{ number_format($service->profit, 2) }} {{ $service->currency }}
                                            </p>
                                            <span
                                                class="px-1.5 py-0.5 text-[10px] font-bold rounded {{ $service->profit >= 0 ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                                                %{{ number_format($service->margin, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <label
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Durum</label>
                                <div class="mt-1 flex items-center space-x-2">
                                    <span
                                        class="w-2 h-2 rounded-full {{ \App\Models\Service::getStatusDotColor($service->status) }}"></span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ \App\Models\Service::getStatusLabel($service->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Başlangıç</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $service->start_date ? $service->start_date->format('d.m.Y') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bitiş
                                        (Vade)</label>
                                    <p class="mt-1 text-sm font-semibold {{ $service->expiry_color }}">
                                        {{ $service->end_date ? $service->end_date->format('d.m.Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Related Entities -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Info -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Müşteri Bilgileri</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $service->customer->name ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $service->customer->email ?? '-' }}
                                </p>
                            </div>
                            @if($service->customer && $service->customer->id)
                                <a href="{{ route('customers.show', $service->customer->id) }}"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Müşteriye Git
                                </a>
                            @endif
                        </div>
                    </x-card>

                    <!-- Provider Info -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sağlayıcı Bilgileri</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $service->provider->name ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $service->provider->website ?? '-' }}
                                </p>
                            </div>
                            @if($service->provider)
                                <a href="{{ route('providers.show', $service->provider) }}"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Sağlayıcıya Git
                                </a>
                            @endif
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
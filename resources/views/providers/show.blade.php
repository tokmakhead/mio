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
                                    {{ $provider->created_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Services -->
                <div class="lg:col-span-2">
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hizmetler</h3>
                        <x-empty-state title="Henüz hizmet yok"
                            message="Bu sağlayıcıya ait henüz bir hizmet kaydı bulunmuyor." cta="Hizmet Ekle"
                            href="#" />
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
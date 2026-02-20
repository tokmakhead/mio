<x-app-layout>
    <x-page-banner title="Yeni Dil Ekle" subtitle="Sistem için yeni bir yerelleştirme paketi oluşturun." />

    <x-settings-tabs />

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl mx-auto space-y-6">

                {{-- Form Card --}}
                <x-card>
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Yeni Dil Ekle</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sistem için yeni bir yerelleştirme
                                paketi oluşturun.</p>
                        </div>
                    </div>

                    <form action="{{ route('settings.languages.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <x-input-label for="locale" :value="__('Dil Kodu')" />
                            <x-text-input id="locale" class="block w-full mt-1" type="text" name="locale"
                                placeholder="Örn: fr, es, it" required autofocus />
                            <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">ISO 639-1 standartında iki harfli
                                dil kodu kullanmanız önerilir.</p>
                            <x-input-error :messages="$errors->get('locale')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-2">
                            <a href="{{ route('settings.languages.index') }}"
                                class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                İptal
                            </a>
                            <x-primary-button>
                                {{ __('Oluştur') }}
                            </x-primary-button>
                        </div>
                    </form>
                </x-card>

                {{-- Info Note --}}
                <div
                    class="flex items-start gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800/30 rounded-lg">
                    <svg class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-primary-700 dark:text-primary-300 leading-relaxed">
                        Yeni dil oluşturulduğunda, sistem otomatik olarak İngilizce (EN) dil paketini temel alarak yeni
                        dosyaları kopyalayacaktır. Çevirileri düzenlemek için oluşturulan dilin kartına
                        tıklayabilirsiniz.
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
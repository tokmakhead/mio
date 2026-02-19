<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Dil Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12 text-gray-900 dark:text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-settings-tabs />

            <div class="max-w-xl mx-auto mt-12">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700 rounded-3xl overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-2xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                    Yeni Dil Ekle</h3>
                                <p class="text-xs text-gray-500 font-medium">Sistem için yeni bir yerelleştirme paketi
                                    oluşturun.</p>
                            </div>
                        </div>

                        <form action="{{ route('settings.languages.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="locale" :value="__('Dil Kodu')"
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1" />
                                <x-text-input id="locale"
                                    class="block w-full rounded-2xl border-gray-200 dark:border-gray-700 focus:border-primary-500 focus:ring-primary-500/20 dark:bg-gray-900/50 shadow-sm transition-all"
                                    type="text" name="locale" placeholder="Örn: fr, es, it" required autofocus />
                                <p class="mt-2 text-[10px] text-gray-400 ml-1">ISO 639-1 standartında iki harfli dil
                                    kodu kullanmanız önerilir.</p>
                                <x-input-error :messages="$errors->get('locale')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4">
                                <a href="{{ route('settings.languages.index') }}"
                                    class="text-xs font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors uppercase tracking-widest">İptal</a>
                                <x-primary-button
                                    class="rounded-full px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-primary-500/20 active:scale-95 transition-all">
                                    {{ __('Oluştur') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <div
                    class="mt-8 p-6 bg-indigo-50/30 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800 rounded-2xl">
                    <div class="flex items-start space-x-3">
                        <div class="p-1.5 bg-indigo-500 rounded text-white mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs text-indigo-800/80 dark:text-indigo-300 font-medium leading-relaxed">
                            Yeni dil oluşturulduğunda, sistem otomatik olarak İngilizce (EN) dil paketini temel alarak
                            yeni dosyaları kopyalayacaktır. Çevirileri düzenlemek için oluşturulan dilin kartına
                            tıklayabilirsiniz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
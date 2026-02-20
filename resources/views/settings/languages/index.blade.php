<x-app-layout>
    <x-page-banner title="Diller & Çeviriler" subtitle="Çeviri dosyalarını yönetin, yeni dil paketleri ekleyin.">
        <x-slot name="actions">
            <a href="{{ route('settings.languages.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg transition-colors duration-200 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Yeni Dil Ekle
            </a>
        </x-slot>
    </x-page-banner>

    <x-settings-tabs />

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($languages as $lang)
                            <x-card class="relative overflow-hidden">
                                {{-- Top color stripe --}}
                                <div class="absolute top-0 left-0 right-0 h-0.5
                                        {{ $lang === 'tr' ? 'bg-red-500' : ($lang === 'en' ? 'bg-blue-500' : 'bg-primary-500') }}">
                                </div>

                                {{-- Card Header --}}
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-black
                                                {{ $lang === 'tr' ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400'
                    : ($lang === 'en' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400'
                        : 'bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400') }}">
                                            {{ strtoupper($lang) }}
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                                {{ $lang === 'tr' ? 'Türkçe' : ($lang === 'en' ? 'English' : strtoupper($lang)) }}
                                            </h3>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Dil Paketi</p>
                                        </div>
                                    </div>

                                    @if($lang !== 'en' && $lang !== 'tr')
                                        <form action="{{ route('settings.languages.destroy', $lang) }}" method="POST"
                                            onsubmit="return confirm('Bu dili silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-danger-500 hover:bg-danger-50 dark:hover:bg-danger-900/20 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                {{-- General Translations --}}
                                <div class="mb-4">
                                    <p
                                        class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 inline-block"></span>
                                        Genel Çeviriler
                                    </p>
                                    <a href="{{ route('settings.languages.edit', ['locale' => $lang, 'file' => 'json']) }}"
                                        class="flex items-center justify-between px-3 py-2.5 bg-gray-50 dark:bg-gray-700/40 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg border border-transparent hover:border-primary-200 dark:hover:border-primary-700 transition-all group">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-500 transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $lang }}.json</span>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-500 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>

                                {{-- System Files --}}
                                <div>
                                    <p
                                        class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-warning-500 inline-block"></span>
                                        Sistem Dosyaları
                                    </p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($files as $file)
                                            <a href="{{ route('settings.languages.edit', ['locale' => $lang, 'file' => $file]) }}"
                                                class="flex items-center gap-1.5 px-2.5 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-warning-600 dark:hover:text-warning-400 hover:bg-warning-50 dark:hover:bg-warning-900/20 rounded-lg border border-transparent hover:border-warning-100 dark:hover:border-warning-700 transition-all">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>{{ str_replace('.php', '', $file) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </x-card>
                @endforeach

                {{-- Add New Language Card --}}
                <a href="{{ route('settings.languages.create') }}"
                    class="group flex flex-col items-center justify-center p-8 bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl hover:border-primary-400 hover:bg-primary-50/30 dark:hover:bg-primary-900/10 transition-all duration-200 min-h-[200px]">
                    <div
                        class="w-12 h-12 bg-white dark:bg-gray-800 rounded-full shadow-sm border border-gray-200 dark:border-gray-700 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:border-primary-300 transition-all duration-200">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span
                        class="text-sm font-semibold text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Yeni
                        Dil Ekle</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
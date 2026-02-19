<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Diller & Çeviriler') }}
            </h2>
            <a href="{{ route('settings.languages.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Yeni Dil Ekle') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-settings-tabs />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                @foreach($languages as $lang)
                    <div
                        class="relative group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <!-- Card Header Decoration -->
                        <div
                            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r {{ $lang === 'tr' ? 'from-red-500 to-red-600' : ($lang === 'en' ? 'from-blue-500 to-blue-600' : 'from-primary-500 to-primary-600') }}">
                        </div>

                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-12 h-12 flex items-center justify-center rounded-xl {{ $lang === 'tr' ? 'bg-red-50 text-red-600' : ($lang === 'en' ? 'bg-blue-50 text-blue-600' : 'bg-primary-50 text-primary-600') }} font-black text-xl shadow-sm">
                                        {{ strtoupper($lang) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                            {{ $lang === 'tr' ? 'Türkçe' : ($lang === 'en' ? 'English' : strtoupper($lang)) }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Dil Paketi</p>
                                    </div>
                                </div>

                                @if($lang !== 'en' && $lang !== 'tr')
                                    <form action="{{ route('settings.languages.destroy', $lang) }}" method="POST"
                                        onsubmit="return confirm('Bu dili silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="space-y-6">
                                <!-- General Translations -->
                                <div>
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2"></span>
                                        Genel Çeviriler
                                    </h4>
                                    <a href="{{ route('settings.languages.edit', ['locale' => $lang, 'file' => 'json']) }}"
                                        class="group/item flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl border border-transparent hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span
                                                class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $lang }}.json</span>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-300 group-hover/item:text-indigo-500 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>

                                <!-- System Files -->
                                <div>
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span>
                                        Sistem Dosyaları
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($files as $file)
                                            <a href="{{ route('settings.languages.edit', ['locale' => $lang, 'file' => $file]) }}"
                                                class="flex items-center space-x-2 px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all border border-transparent hover:border-amber-100 dark:hover:border-amber-500/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span>{{ str_replace('.php', '', $file) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Add New Language Card (Placeholder UX) -->
                <a href="{{ route('settings.languages.create') }}"
                    class="group relative flex flex-col items-center justify-center p-8 bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl hover:border-primary-500 hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 min-h-[400px]">
                    <div
                        class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-500 group-hover:text-primary-600 transition-colors">Yeni
                        Dil Ekle</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
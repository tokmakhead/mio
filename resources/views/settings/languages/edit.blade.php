<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dil Düzenle') }}: {{ strtoupper($locale) }} / {{ $file === 'json' ? $locale . '.json' : $file }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showMissingOnly: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-settings-tabs />

            <div
                class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden mt-8">
                <!-- Header Stats -->
                <div
                    class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold">
                                {{ strtoupper($locale) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Dosya:
                                    {{ $file === 'json' ? $locale . '.json' : $file }}</h3>
                                <p class="text-xs text-gray-500">Çeviri Dosyası Düzenleniyor</p>
                            </div>
                        </div>

                        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden md:block"></div>

                        @if(count($missingKeys) > 0)
                            <div class="flex items-center gap-2 text-sm">
                                <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                                <span class="text-red-600 dark:text-red-400 font-bold">{{ count($missingKeys) }}</span>
                                <span class="text-gray-600 dark:text-gray-400">Eksik Anahtar</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400 font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Hepsi Tamam!
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center cursor-pointer group">
                            <span
                                class="mr-3 text-xs font-bold text-gray-500 group-hover:text-gray-700 transition-colors uppercase tracking-wider">Sadece
                                Eksikler</span>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" x-model="showMissingOnly" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('settings.languages.update', $locale) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="file" value="{{ $file }}">

                        <div class="space-y-4">
                            @foreach($combinedTranslations as $key => $data)
                                @php
                                    $value = $data['value'];
                                    $baseline = $data['baseline'];
                                    $isMissing = ($value === null || $value === '');
                                    $isValueArray = is_array($value);
                                    $isBaselineArray = is_array($baseline);
                                @endphp

                                <div x-show="!showMissingOnly || {{ $isMissing ? 'true' : 'false' }}"
                                    class="group p-4 bg-white dark:bg-gray-800 rounded-xl border {{ $isMissing ? 'border-red-100 dark:border-red-500/20 bg-red-50/10' : 'border-gray-100 dark:border-gray-700' }} hover:border-primary-200 dark:hover:border-primary-500/30 transition-all">

                                    @if($isValueArray || $isBaselineArray)
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center justify-between">
                                                <code
                                                    class="text-[11px] font-bold text-gray-400 dark:text-gray-500">{{ $key }}</code>
                                                <span
                                                    class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 uppercase tracking-widest">Dizi
                                                    (Array)</span>
                                            </div>
                                            <div
                                                class="p-3 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800 rounded-lg text-xs text-amber-800 dark:text-amber-200 italic">
                                                Bu anahtar bir dizi içeriyor. Şu an için sadece metin düzenleme
                                                desteklenmektedir.
                                            </div>
                                            <input type="hidden" name="keys[]" value="{{ $key }}">
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                                            <!-- Key & Reference Column -->
                                            <div class="lg:col-span-4">
                                                <div class="flex flex-col h-full">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <code
                                                            class="text-[10px] font-bold text-gray-400 dark:text-gray-500 break-all">{{ $key }}</code>
                                                        @if($isMissing)
                                                            <span
                                                                class="text-[9px] font-black text-red-500 uppercase tracking-[0.2em]">Eksik</span>
                                                        @endif
                                                    </div>

                                                    <div
                                                        class="flex-grow flex flex-col justify-center p-3 bg-gray-50/50 dark:bg-gray-900/40 rounded-lg border border-gray-100 dark:border-gray-700/50">
                                                        <div
                                                            class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5 flex items-center">
                                                            <span class="w-1 h-1 rounded-full bg-gray-300 mr-1.5"></span>
                                                            İngilizce (Referans)
                                                        </div>
                                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 line-clamp-3"
                                                            title="{{ $baseline }}">
                                                            {{ $baseline ?? '—' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Translation Column -->
                                            <div class="lg:col-span-8">
                                                <div class="relative">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <label
                                                            class="text-[10px] font-bold text-primary-500 uppercase tracking-widest flex items-center">
                                                            <span class="w-1 h-1 rounded-full bg-primary-500 mr-1.5"></span>
                                                            Çeviri ({{ strtoupper($locale) }})
                                                        </label>
                                                        <button type="button"
                                                            @click="$refs.input_{{ Str::slug($key) }}.value = '{{ addslashes($baseline) }}'"
                                                            class="text-xs font-bold text-gray-400 hover:text-primary-500 transition-colors flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                                            </svg>
                                                            Kopyala
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="keys[]" value="{{ $key }}">
                                                    <textarea x-ref="input_{{ Str::slug($key) }}" name="values[]" rows="2"
                                                        placeholder="{{ $baseline ?? 'Çeviri giriniz...' }}"
                                                        class="block w-full rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500/20 dark:bg-gray-900/50 dark:text-white sm:text-sm transition-all {{ $isMissing ? 'bg-red-50/20' : '' }}">{{ $value }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            <!-- New Key Addition -->
                            <div
                                class="p-6 bg-primary-50/30 dark:bg-primary-900/10 border border-dashed border-primary-200 dark:border-primary-500/30 rounded-2xl mt-12">
                                <h4
                                    class="text-sm font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Yeni Anahtar Ekle
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="keys[]" placeholder="Anahtar (Örn: auth.failed)"
                                        class="block w-full rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500/20 dark:bg-gray-900 dark:text-white sm:text-sm">
                                    <input type="text" name="values[]" placeholder="Değer (Çeviri metni)"
                                        class="block w-full rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500/20 dark:bg-gray-900 dark:text-white sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Actions -->
                        <div
                            class="sticky bottom-4 mx-auto max-w-sm mt-12 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border border-gray-200 dark:border-gray-700 p-3 rounded-full shadow-2xl z-50 flex items-center justify-between pr-4 pl-6">
                            <span
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest">Değişiklikleri</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('settings.languages.index') }}"
                                    class="text-xs font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors uppercase tracking-widest">İptal</a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-widest rounded-full shadow-lg shadow-primary-500/20 transition-all active:scale-95">
                                    Kaydet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
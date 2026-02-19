<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dil Düzenle') }}: {{ strtoupper($locale) }} / {{ $file === 'json' ? $locale . '.json' : $file }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showMissingOnly: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-settings-tabs />

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Stats & Filters -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div class="flex items-center gap-4">
                            @if(count($missingKeys) > 0)
                                <div
                                    class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-4 py-2 rounded-lg flex items-center gap-2 border border-red-100 dark:border-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span class="font-bold">{{ count($missingKeys) }}</span> eksik çeviri var.
                                </div>
                            @else
                                <div
                                    class="bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 px-4 py-2 rounded-lg flex items-center gap-2 border border-green-100 dark:border-green-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Tüm çeviriler tam!
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="showMissingOnly" class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600">
                                </div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Sadece Eksikleri
                                    Göster</span>
                            </label>
                        </div>
                    </div>

                    <form action="{{ route('settings.languages.update', $locale) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="file" value="{{ $file }}">

                        <div class="space-y-4 mb-6">
                            @foreach($combinedTranslations as $key => $data)
                                @php
                                    $value = $data['value'];
                                    $baseline = $data['baseline'];
                                    $isMissing = ($value === null || $value === '');
                                    // Complex nested array check - we only support string editing for now at top level or depth 1 technically but controller flattened logic isn't there yet.
                                    // If value is array, show warning.
                                    $isValueArray = is_array($value);
                                    $isBaselineArray = is_array($baseline);
                                @endphp

                                <div x-show="!showMissingOnly || {{ $isMissing ? 'true' : 'false' }}"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 {{ $isMissing ? 'bg-red-50/50 dark:bg-red-900/10 -mx-2 px-2 rounded' : '' }}">

                                    @if($isValueArray || $isBaselineArray)
                                        <div
                                            class="col-span-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 rounded text-xs">
                                            <strong>{{ $key }}</strong> anahtarı bir dizi içeriyor. Şu an için görsel
                                            düzenleyici sadece metin destekler.
                                            <input type="hidden" name="keys[]" value="{{ $key }}">
                                            {{-- Preserve array value by not sending it? Or sending json? --}}
                                            {{-- For safety, we skip sending value input for arrays to avoid overwriting with
                                            string 'Array' --}}
                                            {{-- Ideally we would recurse, but for MVP this is limitation --}}
                                        </div>
                                    @else
                                        <div>
                                            <label
                                                class="block text-xs text-gray-500 dark:text-gray-400 font-mono mb-1 flex justify-between">
                                                <span>{{ $key }}</span>
                                                @if($isMissing)<span
                                                class="text-red-500 font-bold text-[10px] uppercase tracking-wider">Eksik</span>@endif
                                            </label>
                                            <input type="hidden" name="keys[]" value="{{ $key }}">

                                            <div
                                                class="text-sm font-medium text-gray-700 dark:text-gray-300 break-all bg-gray-50 dark:bg-gray-900/50 p-2 rounded min-h-[42px] relative group">
                                                {{ $key }}
                                                @if($baseline)
                                                    <div
                                                        class="hidden group-hover:block absolute z-10 top-full left-0 mt-1 w-full p-2 bg-gray-800 text-white text-xs rounded shadow-lg">
                                                        <strong>EN:</strong> {{ $baseline }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if($baseline)
                                                <div class="mt-1 text-xs text-gray-400 dark:text-gray-500 italic truncate"
                                                    title="{{ $baseline }}">
                                                    EN: {{ Str::limit($baseline, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">
                                                Çeviri ({{ strtoupper($locale) }})
                                                <button type="button"
                                                    @click="$refs.input_{{ Str::slug($key) }}.value = '{{ addslashes($baseline) }}'"
                                                    class="float-right text-indigo-500 hover:text-indigo-700 text-[10px]">EN
                                                    Kopyala</button>
                                            </label>
                                            <textarea x-ref="input_{{ Str::slug($key) }}" name="values[]" rows="2"
                                                placeholder="{{ $baseline ?? 'Çeviri giriniz...' }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white sm:text-sm {{ $isMissing ? 'border-red-300 ring-1 ring-red-100' : '' }}">{{ $value }}</textarea>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- New Key Addition --}}
                        <div
                            class="p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800/50 mb-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Yeni Anahtar Ekle</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" name="keys[]" placeholder="Anahtar (Key)"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white sm:text-sm">
                                </div>
                                <div>
                                    <input type="text" name="values[]" placeholder="Değer (Value)"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-4 sticky bottom-0 bg-white dark:bg-gray-800 p-4 border-t border-gray-100 dark:border-gray-700 -mx-6 -mb-6 shadow-lg z-10">
                            <x-primary-button>{{ __('Kaydet') }}</x-primary-button>
                            <a href="{{ route('settings.languages.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Geri Dön') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-page-banner title="Çevirileri Düzenle: {{ strtoupper($locale) }}"
        subtitle="JSON formatındaki çeviri dosyasını düzenleyebilirsiniz." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <div class="mb-4 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-md border-l-4 border-yellow-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                Lütfen geçerli bir JSON formatı kullandığınızdan emin olun. Hatalı JSON, sitenin
                                çalışmasını bozabilir.
                                <br>Örnek: <code>"Anahtar": "Değer"</code>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Error Modal -->
                <div x-data="{ open: false }" x-on:json-error.window="open = true" class="relative z-50">
                    <div x-show="open" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div x-show="open" class="fixed inset-0 z-10 overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div x-show="open" @click.away="open = false" class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600 dark:text-red-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100" id="modal-title">Geçersiz JSON Formatı</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Girdiğiniz metin geçerli bir JSON formatında değil. Lütfen söz dizimini kontrol edip tekrar deneyin.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="button" @click="open = false" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Tamam</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('settings.languages.update', $locale) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="content" value="JSON İçeriği" />
                        <textarea id="content" name="content" rows="20"
                            class="mt-1 block w-full font-mono text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>{{ $content }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-between">
                        <x-secondary-button type="button" onclick="history.back()">
                            Geri Dön
                        </x-secondary-button>

                        <div class="flex gap-3">
                            <button type="button" onclick="beautifyJson()"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-800 transition ease-in-out duration-150">
                                Formatla (Beautify)
                            </button>
                            <x-primary-button>
                                Kaydet
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function beautifyJson() {
            try {
                const textarea = document.getElementById('content');
                const json = JSON.parse(textarea.value);
                textarea.value = JSON.stringify(json, null, 4);
            } catch (e) {
                window.dispatchEvent(new CustomEvent('json-error'));
            }
        }
    </script>
</x-app-layout>
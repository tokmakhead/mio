<x-app-layout>
    <x-page-banner title="Finansal Ayarlar" subtitle="Banka hesapları ve belge numaralandırma kuralları." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <form action="{{ route('settings.financial.update') }}" method="POST">
                @csrf

                <!-- Bank Accounts Section -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Banka Bilgileri
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Faturalarda ve tekliflerde gösterilecek resmi banka hesap bilgileriniz.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="col-span-2 sm:col-span-1">
                                            <x-input-label for="bank_name" value="Banka Adı" />
                                            <x-text-input id="bank_name" name="bank_name" type="text"
                                                class="mt-1 block w-full" :value="old('bank_name', $settings->bank_name)" placeholder="Örn: Garanti BBVA" />
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <x-input-label for="iban" value="IBAN" />
                                            <x-text-input id="iban" name="iban" type="text"
                                                class="mt-1 block w-full font-mono text-sm" :value="old('iban', $settings->iban)" placeholder="TR00 0000 0000 0000 0000 0000 00" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="bank_account_info" value="Diğer Hesap Bilgileri / Notlar" />
                                        <textarea id="bank_account_info" name="bank_account_info" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 sm:text-sm">{{ old('bank_account_info', $settings->bank_account_info) }}</textarea>
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Şube kodu, hesap sahibi
                                            vb. ek bilgileri buraya girebilirsiniz.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block" aria-hidden="true">
                    <div class="py-5">
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                </div>

                <!-- Numbering Section -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Numaralandırma
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Belgelerinizin otomatik numaralandırma formatını özelleştirin.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-2 gap-8">
                                    <!-- Invoice Config -->
                                    <div class="col-span-2 sm:col-span-1 space-y-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Fatura Ayarları
                                        </h4>
                                        <div>
                                            <x-input-label for="invoice_prefix" value="Ön Ek (Prefix)" />
                                            <x-text-input id="invoice_prefix" name="invoice_prefix" type="text"
                                                class="mt-1 block w-full uppercase font-mono"
                                                :value="old('invoice_prefix', $settings->invoice_prefix)" />
                                        </div>
                                        <div>
                                            <x-input-label for="invoice_start_number" value="Başlangıç No" />
                                            <x-text-input id="invoice_start_number" name="invoice_start_number"
                                                type="number" class="mt-1 block w-full font-mono"
                                                :value="old('invoice_start_number', $settings->invoice_start_number)" />
                                        </div>
                                        <p class="text-xs text-gray-500">Örnek: <span
                                                class="font-mono">{{ $settings->invoice_prefix }}{{ $settings->invoice_start_number }}</span>
                                        </p>
                                    </div>

                                    <!-- Quote Config -->
                                    <div class="col-span-2 sm:col-span-1 space-y-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Teklif Ayarları
                                        </h4>
                                        <div>
                                            <x-input-label for="quote_prefix" value="Ön Ek (Prefix)" />
                                            <x-text-input id="quote_prefix" name="quote_prefix" type="text"
                                                class="mt-1 block w-full uppercase font-mono"
                                                :value="old('quote_prefix', $settings->quote_prefix)" />
                                        </div>
                                        <div>
                                            <x-input-label for="quote_start_number" value="Başlangıç No" />
                                            <x-text-input id="quote_start_number" name="quote_start_number"
                                                type="number" class="mt-1 block w-full font-mono"
                                                :value="old('quote_start_number', $settings->quote_start_number)" />
                                        </div>
                                        <p class="text-xs text-gray-500">Örnek: <span
                                                class="font-mono">{{ $settings->quote_prefix }}{{ $settings->quote_start_number }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-right sm:px-6">
                                <x-primary-button>
                                    Değişiklikleri Kaydet
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
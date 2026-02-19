<x-app-layout>
    <x-page-banner title="Finansal Ayarlar" subtitle="Banka hesapları ve belge numaralandırma kuralları." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Numbering Section (Main Financial Settings) -->
            <form action="{{ route('settings.financial.update') }}" method="POST">
                @csrf
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
                                            Fatura & Teklif Ayarları
                                        </h4>

                                        <!-- Default Due Days -->
                                        <div>
                                            <x-input-label for="default_payment_due_days"
                                                value="Varsayılan Vade (Gün)" />
                                            <x-text-input id="default_payment_due_days" name="default_payment_due_days"
                                                type="number" class="mt-1 block w-full"
                                                :value="old('default_payment_due_days', $settings->default_payment_due_days ?? 14)" />
                                        </div>

                                        <!-- Invoice Numbering -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="invoice_prefix" value="Fatura Ön Eki" />
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
                                        </div>

                                        <!-- Quote Numbering -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="quote_prefix" value="Teklif Ön Eki" />
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
                                        </div>
                                    </div>

                                    <!-- Currency & Formatting -->
                                    <div class="col-span-2 sm:col-span-1 space-y-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Para Birimi & Format
                                        </h4>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="currency_position" value="Sembol Konumu" />
                                                <select id="currency_position" name="currency_position"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                                    <option value="prefix" {{ ($settings->currency_position ?? 'prefix') == 'prefix' ? 'selected' : '' }}>Başta (₺100)</option>
                                                    <option value="suffix" {{ ($settings->currency_position ?? 'prefix') == 'suffix' ? 'selected' : '' }}>Sonda (100₺)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label for="currency_precision" value="Ondalık Hane" />
                                                <x-text-input id="currency_precision" name="currency_precision"
                                                    type="number" min="0" max="4" class="mt-1 block w-full"
                                                    :value="old('currency_precision', $settings->currency_precision ?? 2)" />
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="thousand_separator" value="Binlik Ayırıcı" />
                                                <select id="thousand_separator" name="thousand_separator"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                                    <option value="." {{ ($settings->thousand_separator ?? '.') == '.' ? 'selected' : '' }}>Nokta (.)</option>
                                                    <option value="," {{ ($settings->thousand_separator ?? '.') == ',' ? 'selected' : '' }}>Virgül (,)</option>
                                                    <option value=" " {{ ($settings->thousand_separator ?? '.') == ' ' ? 'selected' : '' }}>Boşluk ( )</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label for="decimal_separator" value="Ondalık Ayırıcı" />
                                                <select id="decimal_separator" name="decimal_separator"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                                    <option value="," {{ ($settings->decimal_separator ?? ',') == ',' ? 'selected' : '' }}>Virgül (,)</option>
                                                    <option value="." {{ ($settings->decimal_separator ?? ',') == '.' ? 'selected' : '' }}>Nokta (.)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <x-input-label for="rounding_rule" value="Yuvarlama Kuralı" />
                                            <select id="rounding_rule" name="rounding_rule"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                                <option value="none" {{ ($settings->rounding_rule ?? 'none') == 'none' ? 'selected' : '' }}>Yok (Tam Hassasiyet)</option>
                                                <option value="nearest_1" {{ ($settings->rounding_rule ?? 'none') == 'nearest_1' ? 'selected' : '' }}>En Yakın Tam Sayı</option>
                                                <option value="nearest_10" {{ ($settings->rounding_rule ?? 'none') == 'nearest_10' ? 'selected' : '' }}>En Yakın 0.10</option>
                                                <option value="up" {{ ($settings->rounding_rule ?? 'none') == 'up' ? 'selected' : '' }}>Yukarı Yuvarla</option>
                                                <option value="down" {{ ($settings->rounding_rule ?? 'none') == 'down' ? 'selected' : '' }}>Aşağı Yuvarla</option>
                                            </select>
                                            <p class="mt-1 text-xs text-gray-500">Fatura toplam tutarı için hesaplama
                                                yöntemi.</p>
                                        </div>
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

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                </div>
            </div>

            <!-- Bank Accounts Section -->
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Banka Hesapları
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Faturalarda ve tekliflerde kullanılacak resmi banka hesaplarını yönetin.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2 space-y-6">

                    <!-- Existing Accounts List -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($bankAccounts as $account)
                                <li class="p-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white">{{ $account->bank_name }}</span>
                                                <span
                                                    class="px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $account->currency }}
                                                </span>
                                                @if(!$account->is_active)
                                                    <span
                                                        class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">Pasif</span>
                                                @endif
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                {{ $account->formatted_iban }}
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('settings.bank-accounts.destroy', $account) }}"
                                                method="POST"
                                                onsubmit="return confirm('Bu hesabı silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="p-4 sm:px-6 text-center text-gray-500 py-8">
                                    Henüz banka hesabı eklenmemiş.
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Add New Bank Account -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-6">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Yeni Hesap Ekle</h4>
                        <form action="{{ route('settings.bank-accounts.store') }}" method="POST"
                            class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            @csrf

                            <!-- Bank Name -->
                            <div class="sm:col-span-3">
                                <x-input-label for="bank_name" value="Banka Adı" />
                                <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full"
                                    required placeholder="Örn: Garanti BBVA" />
                            </div>

                            <!-- Currency -->
                            <div class="sm:col-span-3">
                                <x-input-label for="currency" value="Para Birimi" />
                                <select id="currency" name="currency"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="TRY">TRY (Türk Lirası)</option>
                                    <option value="USD">USD (Amerikan Doları)</option>
                                    <option value="EUR">EUR (Euro)</option>
                                    <option value="GBP">GBP (İngiliz Sterlini)</option>
                                </select>
                            </div>

                            <!-- IBAN -->
                            <div class="sm:col-span-6">
                                <x-input-label for="iban" value="IBAN" />
                                <x-text-input id="iban" name="iban" type="text" class="mt-1 block w-full font-mono"
                                    required placeholder="TR..." />
                            </div>

                            <!-- Branch Info (Optional) -->
                            <div class="sm:col-span-2">
                                <x-input-label for="branch_name" value="Şube Adı" />
                                <x-text-input id="branch_name" name="branch_name" type="text"
                                    class="mt-1 block w-full" />
                            </div>
                            <div class="sm:col-span-2">
                                <x-input-label for="branch_code" value="Şube Kodu" />
                                <x-text-input id="branch_code" name="branch_code" type="text"
                                    class="mt-1 block w-full" />
                            </div>
                            <div class="sm:col-span-2">
                                <x-input-label for="account_number" value="Hesap No" />
                                <x-text-input id="account_number" name="account_number" type="text"
                                    class="mt-1 block w-full" />
                            </div>

                            <div class="sm:col-span-6 flex justify-end">
                                <x-primary-button>
                                    Hesap Ekle
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
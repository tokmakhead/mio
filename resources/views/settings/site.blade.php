<x-app-layout>
    <x-page-banner title="Sistem Ayarları"
        subtitle="Genel site ayarlarını ve tercihlerinizi buradan yapılandırabilirsiniz." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <form action="{{ route('settings.site.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- General Settings Section -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Genel Ayarlar
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Sitenizin temel yapılandırma ayarları.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-1 gap-6">

                                    <!-- Site Name -->
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-input-label for="site_name" value="Site Adı" />
                                        <x-text-input id="site_name" name="site_name" type="text"
                                            class="mt-1 block w-full" :value="old('site_name', $settings->site_name)"
                                            required />
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Tarayıcı başlıklarında
                                            ve e-postalarda şirket adı olarak görünecektir.</p>
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

                <!-- Financial Defaults Section -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Finansal
                                Varsayılanlar</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Sistem genelinde kullanılacak varsayılan para birimi ve vergi oranları.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="default_currency" value="Para Birimi" />
                                        <select id="default_currency" name="default_currency"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            <option value="TRY" {{ $settings->default_currency == 'TRY' ? 'selected' : '' }}>Türk Lirası (₺)</option>
                                            <option value="USD" {{ $settings->default_currency == 'USD' ? 'selected' : '' }}>Amerikan Doları ($)</option>
                                            <option value="EUR" {{ $settings->default_currency == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                        </select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="default_vat_rate" value="Varsayılan KDV Oranı (%)" />
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <x-text-input id="default_vat_rate" name="default_vat_rate" type="number"
                                                step="0.01" class="block w-full pr-12" :value="old('default_vat_rate', $settings->default_vat_rate)" required />
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="withholding_rate" value="Varsayılan Tevkifat Oranı (%)" />
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <x-text-input id="withholding_rate" name="withholding_rate" type="number"
                                                step="0.01" class="block w-full pr-12" :value="old('withholding_rate', $settings->withholding_rate)" required />
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-right sm:px-6">
                                <x-primary-button>
                                    Ayarları Kaydet
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
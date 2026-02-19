<x-app-layout>
    <x-page-banner title="Firma ve Genel Ayarlar"
        subtitle="Sistem yapılandırması ve firma kimlik bilgileri." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <form action="{{ route('settings.site.update') }}" method="POST">
                @csrf

                <!-- Identity Section -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Firma Kimliği</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Sistemde ve faturalarda görünecek firma ünvanı ve panel adı.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Site Name (System) -->
                                        <div>
                                            <x-input-label for="site_name" value="Panel Adı (Sistem)" />
                                            <x-text-input id="site_name" name="site_name" type="text"
                                                class="mt-1 block w-full" :value="old('site_name', $settings->site_name)"
                                                placeholder="Örn: MIONEX Panel" required />
                                            <p class="mt-1 text-xs text-gray-500">Tarayıcı sekmesinde görünür.</p>
                                        </div>

                                        <!-- Site Title (Company) -->
                                        <div>
                                            <x-input-label for="site_title" value="Firma Ünvanı (Marka)" />
                                            <x-text-input id="site_title" name="site_title" type="text"
                                                class="mt-1 block w-full" :value="old('site_title', $brandSettings['site_title'] ?? '')" 
                                                placeholder="Örn: Mionex Teknoloji A.Ş." />
                                            <p class="mt-1 text-xs text-gray-500">Fatura başlığı ve e-postalarda görünür.</p>
                                        </div>
                                    </div>

                                    <!-- Company Contact Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="company_phone" :value="__('Telefon')" />
                                            <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full" 
                                                :value="old('company_phone', $brandSettings['company_phone'] ?? '')" placeholder="+90 555 000 0000" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_email" :value="__('E-posta')" />
                                            <x-text-input id="company_email" name="company_email" type="email" class="mt-1 block w-full" 
                                                :value="old('company_email', $brandSettings['company_email'] ?? '')" placeholder="info@example.com" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="company_address" :value="__('Adres')" />
                                        <x-textarea-input id="company_address" name="company_address" class="mt-1 block w-full" rows="2"
                                            :value="old('company_address', $brandSettings['company_address'] ?? '')" placeholder="Şirket tam adresi..." />
                                    </div>

                                    <!-- Tax Info -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="company_mersis" :value="__('Mersis No')" />
                                            <x-text-input id="company_mersis" name="company_mersis" type="text" class="mt-1 block w-full" 
                                                :value="old('company_mersis', $brandSettings['company_mersis'] ?? '')" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_tax_office" :value="__('Vergi Dairesi')" />
                                            <x-text-input id="company_tax_office" name="company_tax_office" type="text" class="mt-1 block w-full" 
                                                :value="old('company_tax_office', $brandSettings['company_tax_office'] ?? '')" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_tax_id" :value="__('Vergi No')" />
                                            <x-text-input id="company_tax_id" name="company_tax_id" type="text" class="mt-1 block w-full" 
                                                :value="old('company_tax_id', $brandSettings['company_tax_id'] ?? '')" />
                                        </div>
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

                <!-- Regional Settings -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Bölgesel Ayarlar</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Saat dilimi ve varsayılan sistem dili.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="timezone" value="Saat Dilimi" />
                                        <select id="timezone" name="timezone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            @foreach(timezone_identifiers_list() as $timezone)
                                                <option value="{{ $timezone }}" {{ $settings->timezone == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="locale" value="Varsayılan Dil" />
                                        <select id="locale" name="locale" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            <option value="tr" {{ $settings->locale == 'tr' ? 'selected' : '' }}>Türkçe</option>
                                            <option value="en" {{ $settings->locale == 'en' ? 'selected' : '' }}>English</option>
                                        </select>
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
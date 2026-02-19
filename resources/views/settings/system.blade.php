<x-app-layout>
    <x-page-banner title="Sistem Ayarları" subtitle="Bölgesel yapılandırma ve bakım araçları." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Localization -->
            <form action="{{ route('settings.system.update') }}" method="POST">
                @csrf
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Bölgesel Ayarlar
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Tarih, saat ve dil tercihlerinizi yapılandırın.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="timezone" value="Zaman Dilimi" />
                                        <select id="timezone" name="timezone"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            <option value="Europe/Istanbul" {{ $settings->timezone == 'Europe/Istanbul' ? 'selected' : '' }}>Europe/Istanbul</option>
                                            <option value="UTC" {{ $settings->timezone == 'UTC' ? 'selected' : '' }}>UTC
                                            </option>
                                            <option value="Europe/London" {{ $settings->timezone == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                            <option value="America/New_York" {{ $settings->timezone == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        </select>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="locale" value="Dil" />
                                        <select id="locale" name="locale"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            <option value="tr" {{ $settings->locale == 'tr' ? 'selected' : '' }}>Türkçe
                                            </option>
                                            <option value="en" {{ $settings->locale == 'en' ? 'selected' : '' }}>English
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-right sm:px-6">
                                <x-primary-button>Kaydet</x-primary-button>
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

            <!-- Server Status -->
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Sunucu Durumu</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Disk kullanımı ve sistem bilgileri.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div
                        class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                        <div class="px-4 py-5 space-y-6 sm:p-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Disk Kullanımı</h4>
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span
                                                class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-primary-600 bg-primary-200 uppercase last:mr-0 mr-1">
                                                {{ $diskUsage['percent'] }}% Dolu
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-xs font-semibold inline-block text-gray-600 dark:text-gray-400">
                                                {{ $diskUsage['used'] }} / {{ $diskUsage['total'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-primary-200 dark:bg-gray-700">
                                        <div style="width:{{ $diskUsage['percent'] }}%"
                                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500">
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>Boş Alan: {{ $diskUsage['free'] }}</span>
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

            <!-- Maintenance Actions -->
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Sistem Bakımı</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Performans sorunlarını gidermek için önbellek ve log yönetimi.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <!-- Update Center -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Yazılım Güncellemeleri</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sisteminizin güncel olup olmadığını
                                kontrol edin.</p>
                        </div>

                        <div class="p-6">
                            @if(session('update_info'))
                                <div class="mb-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 w-full">
                                            <h3 class="text-lg font-medium text-blue-900 dark:text-blue-200">
                                                Yeni Sürüm Mevcut: v{{ session('update_info')['version'] }}
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-800 dark:text-blue-300 prose prose-sm dark:prose-invert max-w-none">
                                                {!! session('update_info')['release_notes_html'] ?? '' !!}
                                            </div>
                                            <div class="mt-4">
                                                <a href="{{ session('update_info')['download_url'] }}" target="_blank"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    İndir ve Güncelle
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-6">
                                <form action="{{ route('settings.system.update') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <x-input-label for="license_key" value="Lisans Anahtarı" />
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="text" name="license_key" id="license_key"
                                                class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                                placeholder="MIO-XXXX-XXXX-XXXX"
                                                value="{{ $settings->license_key ? str_repeat('*', 8) . substr(Crypt::decryptString($settings->license_key), -4) : '' }}">
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Kaydet
                                            </button>
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Güncellemeleri
                                            alabilmek için geçerli bir lisans anahtarı gereklidir.</p>
                                    </div>
                                </form>

                                <div
                                    class="pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Mevcut Sürüm
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            v{{ config('app.version', '1.0.0') }}</div>
                                    </div>
                                    <form action="{{ route('settings.check-update') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            Güncellemeleri Kontrol Et
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden divide-y divide-gray-200 dark:divide-gray-700">

                        <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Uygulama
                                    Önbelleği</h3>
                                <div class="mt-2 max-w-xl text-sm text-gray-500">
                                    <p>Yapılandırma, rota ve görünüm önbelleklerini temizler. Değişiklikleriniz
                                        görünmüyorsa kullanın.</p>
                                </div>
                            </div>
                            <div class="mt-0 ml-4 shrink-0">
                                <form action="{{ route('settings.cache.clear') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        Önbelleği Temizle
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Sistem
                                    Logları</h3>
                                <div class="mt-2 max-w-xl text-sm text-gray-500">
                                    <p>Laravel log dosyasını (laravel.log) tamamen siler. Bu işlem geri alınamaz.</p>
                                </div>
                            </div>
                            <div class="mt-0 ml-4 shrink-0">
                                <form action="{{ route('settings.logs.clear') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center rounded-md bg-red-50 dark:bg-red-900/20 px-3 py-2 text-sm font-semibold text-red-600 dark:text-red-400 shadow-sm ring-1 ring-inset ring-red-100 dark:ring-red-900 hover:bg-red-100 dark:hover:bg-red-900/40">
                                        Logları Sil
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Log Viewer -->
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Log Önizleme (Son 100
                                Satır)</h3>
                            <div class="bg-gray-900 rounded-lg p-4 h-64 overflow-y-auto">
                                <pre
                                    class="text-xs text-gray-300 font-mono whitespace-pre-wrap">{{ implode("\n", $logs) }}</pre>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
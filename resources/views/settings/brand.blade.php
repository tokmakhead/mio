<x-app-layout>
    <x-page-banner title="Marka ve Görünüm"
        subtitle="Logo, renkler ve giriş ekranı özelleştirmeleri." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <form action="{{ route('settings.brand.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Visual Identity -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Görsel Kimlik</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Sitenizin renk temasını ve logolarını buradan yönetebilirsiniz.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                
                                <!-- Primary Color -->
                                <div>
                                    <x-input-label for="primary_color" :value="__('Ana Renk (Primary Color)')" />
                                    <div class="flex items-center gap-4 mt-1">
                                        <div class="relative">
                                            <input type="color" id="primary_color_picker"
                                                class="h-10 w-16 opacity-0 absolute inset-0 cursor-pointer"
                                                value="{{ old('primary_color', $settings['primary_color'] ?? '#4f46e5') }}"
                                                onchange="updatePrimaryColor(this.value)">
                                            <div id="color_preview" class="h-10 w-16 rounded border border-gray-300 dark:border-gray-700 shadow-sm"
                                                 style="background-color: {{ old('primary_color', $settings['primary_color'] ?? '#4f46e5') }}"></div>
                                        </div>
                        
                                        <x-text-input id="primary_color" name="primary_color" type="text" class="block w-full sm:w-48 font-mono"
                                            :value="old('primary_color', $settings['primary_color'] ?? '#4f46e5')"
                                            placeholder="#4f46e5"
                                            oninput="updatePrimaryColor(this.value)" />
                        
                                        <button type="button" onclick="resetToOriginal()"
                                            class="px-3 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 whitespace-nowrap">
                                            Orijinale Dön
                                        </button>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Sistem genelindeki butonlar ve vurgular bu rengi kullanacaktır.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('primary_color')" />
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                                <!-- Logos -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Logo Upload -->
                                    <div>
                                        <x-input-label for="logo" :value="__('Site Logosu')" />
                                        <div class="mt-2 flex items-center gap-x-3">
                                            @if(isset($settings['logo_path']))
                                                <img class="h-12 w-auto object-contain bg-gray-50 dark:bg-gray-700 rounded p-1" src="{{ $settings['logo_path'] }}" alt="Logo">
                                            @else
                                                <span class="h-12 w-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800">
                                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </span>
                                            @endif
                                            <input type="file" name="logo" id="logo" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Önerilen: 200x50px PNG/SVG.</p>
                                    </div>

                                    <!-- Favicon Upload -->
                                    <div>
                                        <x-input-label for="favicon" :value="__('Favicon')" />
                                        <div class="mt-2 flex items-center gap-x-3">
                                            @if(isset($settings['favicon_path']))
                                                <img class="h-8 w-8 object-contain bg-gray-50 dark:bg-gray-700 rounded p-1" src="{{ $settings['favicon_path'] }}" alt="Favicon">
                                            @else
                                                <span class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/>
                                                    </svg>
                                                </span>
                                            @endif
                                            <input type="file" name="favicon" id="favicon" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Önerilen: 32x32px veya 64x64px PNG/ICO.</p>
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

                <!-- Login Screen -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Giriş Ekranı</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Giriş sayfasında gösterilecek görsel.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div>
                                    <x-input-label for="login_image" :value="__('Giriş Görseli')" />
                                    <div class="mt-2">
                                        @if(isset($settings['login_image_path']))
                                            <div class="mb-4">
                                                <img src="{{ $settings['login_image_path'] }}" alt="Login Screen" class="h-48 w-auto object-cover rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">
                                            </div>
                                        @endif
                                        <input type="file" name="login_image" id="login_image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Giriş sayfasının sol tarafında (veya arka planında) gösterilir. Yüksek çözünürlüklü olması önerilir.</p>
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

            <script>
                function updatePrimaryColor(color) {
                    document.getElementById('color_preview').style.backgroundColor = color;
                    if(document.getElementById('primary_color').value !== color) {
                        document.getElementById('primary_color').value = color;
                    }
                }
            
                function resetToOriginal() {
                    const defaultColor = '#4f46e5';
                    updatePrimaryColor(defaultColor);
                    document.getElementById('primary_color_picker').value = defaultColor;
                }
            </script>
        </div>
    </div>
</x-app-layout>

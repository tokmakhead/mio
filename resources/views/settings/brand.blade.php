<x-app-layout>
    <x-page-banner title="Marka ve Görünüm" subtitle="Sitenizin logosunu, renklerini ve giriş ekranı görselini özelleştirin." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <form method="post" action="{{ route('settings.brand.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Brand Identity Section -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Marka Kimliği</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Sitenizin başlığı ve ana renk tercihi.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-1 gap-6">
                                    
                                    <!-- Site Title -->
                                    <div>
                                        <x-input-label for="site_title" :value="__('Firma Adı')" />
                                        <x-text-input id="site_title" name="site_title" type="text" class="mt-1 block w-full" 
                                            :value="old('site_title', $settings['site_title'] ?? config('app.name'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('site_title')" />
                                    </div>

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

                                    <!-- Logo Upload -->
                                    <div>
                                        <x-input-label for="logo" value="Logo" />
                                        <div class="mt-1 flex items-center space-x-4">
                                            @if(isset($systemSettings->logo_path) && $systemSettings->logo_path)
                                                <img src="{{ asset('storage/' . $systemSettings->logo_path) }}" alt="Logo" class="h-16 w-auto border border-gray-200 dark:border-gray-700 rounded">
                                            @endif
                                            <input id="logo" name="logo" type="file" accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-primary-50 file:text-primary-700
                                                hover:file:bg-primary-100
                                                dark:file:bg-primary-900 dark:file:text-primary-300" />
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG veya SVG. Maksimum 2MB.</p>
                                        <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                                    </div>

                                    <!-- Favicon Upload -->
                                    <div>
                                        <x-input-label for="favicon" value="Favicon" />
                                        <div class="mt-1 flex items-center space-x-4">
                                            @if(isset($systemSettings->favicon_path) && $systemSettings->favicon_path)
                                                <img src="{{ asset('storage/' . $systemSettings->favicon_path) }}" alt="Favicon" class="h-8 w-auto border border-gray-200 dark:border-gray-700 rounded">
                                            @endif
                                            <input id="favicon" name="favicon" type="file" accept="image/png,image/jpeg,image/jpg,image/x-icon"
                                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-primary-50 file:text-primary-700
                                                hover:file:bg-primary-100
                                                dark:file:bg-primary-900 dark:file:text-primary-300" />
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG veya ICO. Maksimum 1MB.</p>
                                        <x-input-error class="mt-2" :messages="$errors->get('favicon')" />
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

                <!-- Visual Assets Section -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Görseller</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Logo, favicon ve arka plan görselleri.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="grid grid-cols-1 gap-8">

                                    <!-- Login Background -->
                                    <div class="col-span-2">
                                        <x-input-label for="login_image" :value="__('Giriş Ekranı Görseli (Sol Taraf)')" />
                                        <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 dark:border-gray-700 px-6 py-10">
                                            <div class="text-center w-full">
                                                @if(isset($settings['login_image_path']))
                                                    <div class="mx-auto mb-4 relative h-32 w-full max-w-md overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-900">
                                                        <img src="{{ $settings['login_image_path'] }}" alt="Login Image" class="h-full w-full object-cover">
                                                    </div>
                                                @endif
                                                <div class="mt-4 flex text-sm leading-6 text-gray-600 dark:text-gray-400 justify-center">
                                                    <label for="login_image" class="relative cursor-pointer rounded-md bg-white dark:bg-gray-800 font-semibold text-primary-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-primary-600 focus-within:ring-offset-2 hover:text-primary-500">
                                                        <span>Yeni Görsel Yükle</span>
                                                        <input id="login_image" name="login_image" type="file" accept="image/*" class="sr-only">
                                                    </label>
                                                </div>
                                                <p class="text-xs leading-5 text-gray-600 dark:text-gray-400">Yüksek çözünürlüklü dikey/yatay (Max 4MB)</p>
                                            </div>
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('login_image')" />
                                    </div>

                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-right sm:px-6">
                                <x-primary-button>
                                    {{ __('Değişiklikleri Kaydet') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        const ORIGINAL_COLOR = '#de4968';

        function updatePrimaryColor(hex) {
            if (!/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(hex)) return;
            
            const rgb = hexToRgb(hex);
            if (!rgb) return;

            // Helper to set variable
            const setVar = (level, r, g, b) => {
                document.documentElement.style.setProperty(`--color-primary-${level}`, `${r} ${g} ${b}`);
            };

            // Helper to blend color
            const blend = (c1, c2, ratio) => {
                return [
                    Math.round(c1.r * (1 - ratio) + c2.r * ratio),
                    Math.round(c1.g * (1 - ratio) + c2.g * ratio),
                    Math.round(c1.b * (1 - ratio) + c2.b * ratio)
                ];
            };

            // Set all shades to ensure hero and button effects work
            setVar(500, rgb.r, rgb.g, rgb.b);
            
            // Lighter shades
            setVar(50, ...blend(rgb, {r:255, g:255, b:255}, 0.95));
            setVar(100, ...blend(rgb, {r:255, g:255, b:255}, 0.85));
            setVar(200, ...blend(rgb, {r:255, g:255, b:255}, 0.7));
            setVar(300, ...blend(rgb, {r:255, g:255, b:255}, 0.5));
            setVar(400, ...blend(rgb, {r:255, g:255, b:255}, 0.3));

            // Darker shades
            setVar(600, ...blend(rgb, {r:0, g:0, b:0}, 0.15));
            setVar(700, ...blend(rgb, {r:0, g:0, b:0}, 0.3));
            setVar(800, ...blend(rgb, {r:0, g:0, b:0}, 0.45));
            setVar(900, ...blend(rgb, {r:0, g:0, b:0}, 0.6));
            
            // Update Previews
            document.getElementById('primary_color_picker').value = hex;
            document.getElementById('primary_color').value = hex;
            document.getElementById('color_preview').style.backgroundColor = hex;
        }

        function hexToRgb(hex) {
            var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
            hex = hex.replace(shorthandRegex, function(m, r, g, b) {
                return r + r + g + g + b + b;
            });
            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        function resetToOriginal() {
            updatePrimaryColor(ORIGINAL_COLOR);
        }
    </script>
    @endpush
</x-app-layout>

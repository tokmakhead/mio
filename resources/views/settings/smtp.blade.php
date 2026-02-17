<x-app-layout>
    <x-page-banner title="SMTP Ayarları" subtitle="Giden e-posta sunucusu yapılandırması." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <div class="md:grid md:grid-cols-3 md:gap-6">
                <!-- Info Section -->
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Sunucu Bilgileri</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            E-postaların gönderileceği SMTP sunucusunun bağlantı bilgilerini yapılandırın.
                        </p>

                        <div class="mt-6">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Güvenlik
                                İpuçları</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Şifreniz veritabanında şifrelenerek saklanır.
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    TLS/SSL kullanımı önerilir.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <!-- SMTP Presets -->
                    <div class="mb-6 grid grid-cols-2 gap-4">
                        <button type="button" onclick="applyPreset('gmail')"
                            class="flex items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-all group">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm border border-gray-100">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                        <path fill="#34A853"
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                        <path fill="#FBBC05"
                                            d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.83z" />
                                        <path fill="#EA4335"
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.83c.87-2.6 3.3-4.53 6.16-4.53z" />
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Gmail Preset</span>
                            </div>
                        </button>
                        <button type="button" onclick="applyPreset('outlook')"
                            class="flex items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all group">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-600 text-white shadow-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 20V4l-7 2v12l7 2M12 4v16l9-2.5V6.5L12 4z" />
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Outlook Preset</span>
                            </div>
                        </button>
                    </div>

                    <form action="{{ route('settings.smtp.update') }}" method="POST">
                        @csrf
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 space-y-6 sm:p-6">

                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <x-input-label for="host" value="SMTP Host" />
                                        <x-text-input id="host" name="host" type="text" class="mt-1 block w-full"
                                            :value="old('host', $settings->host)" placeholder="smtp.gmail.com" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-2">
                                        <x-input-label for="port" value="Port" />
                                        <x-text-input id="port" name="port" type="text" class="mt-1 block w-full"
                                            :value="old('port', $settings->port)" placeholder="587" />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="username" value="Kullanıcı Adı" />
                                        <x-text-input id="username" name="username" type="text"
                                            class="mt-1 block w-full" :value="old('username', $settings->username)" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="password" value="Şifre" />
                                        <x-text-input id="password" name="password" type="password"
                                            class="mt-1 block w-full" placeholder="••••••••" />
                                        <p class="mt-1 text-xs text-gray-500">Değiştirmek istemiyorsanız boş bırakın.
                                        </p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="encryption" value="Şifreleme (Encryption)" />
                                        <select id="encryption" name="encryption"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            <option value="tls" {{ $settings->encryption == 'tls' ? 'selected' : '' }}>TLS
                                            </option>
                                            <option value="ssl" {{ $settings->encryption == 'ssl' ? 'selected' : '' }}>SSL
                                            </option>
                                            <option value="" {{ $settings->encryption == '' ? 'selected' : '' }}>Yok
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="driver" value="Driver" />
                                        <x-text-input id="driver" name="driver" type="text"
                                            class="mt-1 block w-full bg-gray-50" value="smtp" readonly />
                                    </div>

                                    <div class="col-span-6">
                                        <div class="border-t border-gray-100 dark:border-gray-700 my-2"></div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="from_email" value="Gönderen E-posta" />
                                        <x-text-input id="from_email" name="from_email" type="email"
                                            class="mt-1 block w-full" :value="old('from_email', $settings->from_email)" />
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-input-label for="from_name" value="Gönderen Adı" />
                                        <x-text-input id="from_name" name="from_name" type="text"
                                            class="mt-1 block w-full" :value="old('from_name', $settings->from_name)" />
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 text-right sm:px-6">
                                <x-primary-button>Ayarları Kaydet</x-primary-button>
                            </div>
                        </div>
                    </form>

                    <!-- Test Email Form -->
                    <div class="mt-8">
                        <div
                            class="shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:overflow-hidden bg-white dark:bg-gray-800">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Bağlantı Testi
                                </h3>
                                <form id="smtpTestForm" action="{{ route('settings.smtp.test') }}" method="POST"
                                    class="flex gap-4 items-end" onsubmit="return validateSmtpTest()">
                                    @csrf
                                    <div class="flex-grow relative">
                                        <x-input-label for="test_email" value="Test E-postası Alıcısı" />
                                        <x-text-input id="test_email" name="to" type="email" class="mt-1 block w-full"
                                            placeholder="test@alici.com"
                                            oninput="document.getElementById('smtp_email_error').classList.add('hidden')" />

                                        <!-- Premium Validation Tooltip -->
                                        <div id="smtp_email_error" class="hidden absolute z-50 mt-1 left-2">
                                            <div
                                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl p-2.5 flex items-center space-x-2.5 whitespace-nowrap animate-in fade-in slide-in-from-top-1 duration-200">
                                                <div class="bg-orange-500 p-1 rounded-md text-white">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300">Lütfen
                                                    bu alanı doldurun.</span>
                                            </div>
                                            <!-- Tail -->
                                            <div
                                                class="absolute -top-1 left-4 w-2 h-2 bg-white dark:bg-gray-800 border-t border-l border-gray-200 dark:border-gray-700 transform rotate-45">
                                            </div>
                                        </div>
                                    </div>
                                    <x-secondary-button type="submit" class="mb-0.5">Test Gönder</x-secondary-button>
                                </form>
                                <script>
                                    function validateSmtpTest() {
                                        const email = document.getElementById('test_email').value;
                                        const error = document.getElementById('smtp_email_error');
                                        if (!email || !email.includes('@')) {
                                            error.classList.remove('hidden');
                                            return false;
                                        }
                                        return true;
                                    }
                                </script>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function applyPreset(type) {
                const presets = {
                    gmail: {
                        host: 'smtp.gmail.com',
                        port: '587',
                        encryption: 'tls'
                    },
                    outlook: {
                        host: 'smtp.office365.com',
                        port: '587',
                        encryption: 'tls'
                    }
                };

                const data = presets[type];
                if (data) {
                    document.getElementById('host').value = data.host;
                    document.getElementById('port').value = data.port;
                    document.getElementById('encryption').value = data.encryption;
                }
            }
        </script>
    @endpush
</x-app-layout>
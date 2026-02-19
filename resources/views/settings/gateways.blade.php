<x-app-layout>
    <x-page-banner title="Ödeme Altyapıları" subtitle="Stripe ve Iyzico entegrasyonlarını buradan yönetebilirsiniz." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <form action="{{ route('settings.gateways.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div x-data="{ 
                        activeTab: 'stripe',
                        testLoading: false,
                        testMessage: '',
                        testStatus: '',
                        async testGateway(provider) {
                            this.testLoading = true;
                            this.testMessage = '';
                            this.testStatus = '';
                            try {
                                const response = await fetch(`/settings/gateways/test/${provider}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                });
                                const data = await response.json();
                                this.testMessage = data.message;
                                this.testStatus = data.success ? 'success' : 'error';
                            } catch (error) {
                                this.testMessage = 'Bağlantı hatası oluştu.';
                                this.testStatus = 'error';
                            } finally {
                                this.testLoading = false;
                            }
                        }
                    }">
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                @foreach(['stripe', 'iyzico', 'paytr', 'param', 'paypal'] as $provider)
                                    <button type="button" @click="activeTab = '{{ $provider }}'"
                                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === '{{ $provider }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== '{{ $provider }}' }"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm capitalize">
                                        {{ $provider }}
                                    </button>
                                @endforeach
                            </nav>
                        </div>

                        <!-- Test Result Notification -->
                        <div x-show="testMessage" x-transition class="mb-6 p-4 rounded-md"
                            :class="testStatus === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg x-show="testStatus === 'success'" class="h-5 w-5 text-green-400"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <svg x-show="testStatus === 'error'" class="h-5 w-5 text-red-400"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium" x-text="testMessage"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Stripe -->
                        <div x-show="activeTab === 'stripe'" class="space-y-6">
                            @php
                                $stripe = $gateways['stripe'] ?? null;
                                $stripeConfig = $stripe ? $stripe->config : [];
                            @endphp

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Stripe
                                        Ayarları</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Global ödemeler için Stripe
                                        yapılandırması.</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="button" @click="testGateway('stripe')" :disabled="testLoading"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                        <svg x-show="testLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Bağlantıyı Test Et
                                    </button>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="stripe_active" name="stripe[active]" value="1" {{ $stripe && $stripe->is_active ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="stripe_active"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                            Et</label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700 dark:text-blue-200">
                                            Webhook URL'niz: <code
                                                class="font-mono font-bold">{{ route('webhooks.stripe') }}</code><br>
                                            Stripe Dashboard > Developers > Webhooks menüsünden bu URL'i ekleyin.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <x-input-label for="stripe_api_key" value="Public Key (API Key)" />
                                    <x-text-input id="stripe_api_key" name="stripe[api_key]" type="text"
                                        class="mt-1 block w-full" :value="$stripeConfig['api_key'] ?? ''" />
                                </div>

                                <div class="sm:col-span-3">
                                    <x-input-label for="stripe_secret_key" value="Secret Key" />
                                    <x-text-input id="stripe_secret_key" name="stripe[secret_key]" type="password"
                                        class="mt-1 block w-full"
                                        placeholder="{{ !empty($stripeConfig['secret_key']) ? '•••••••• (Kayıtlı - Değiştirmek için doldurun)' : '' }}" />
                                </div>

                                <div class="sm:col-span-6">
                                    <x-input-label for="stripe_webhook_secret" value="Webhook Secret" />
                                    <x-text-input id="stripe_webhook_secret" name="stripe[webhook_secret]"
                                        type="password" class="mt-1 block w-full"
                                        placeholder="{{ !empty($stripeConfig['webhook_secret']) ? '•••••••• (Kayıtlı - Değiştirmek için doldurun)' : '' }}" />
                                </div>

                                <div class="sm:col-span-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="stripe_sandbox" name="stripe[sandbox]" value="1" {{ ($stripeConfig['mode'] ?? '') === 'sandbox' ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="stripe_sandbox"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Test Modu
                                            (Sandbox)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Iyzico -->
                        <div x-show="activeTab === 'iyzico'" class="space-y-6" style="display: none;">
                            @php
                                $iyzico = $gateways['iyzico'] ?? null;
                                $iyzicoConfig = $iyzico ? $iyzico->config : [];
                            @endphp

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Iyzico
                                        Ayarları</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Türkiye odaklı ödemeler
                                        için Iyzico yapılandırması.</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="button" @click="testGateway('iyzico')" :disabled="testLoading"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                        <svg x-show="testLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Bağlantıyı Test Et
                                    </button>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="iyzico_active" name="iyzico[active]" value="1" {{ $iyzico && $iyzico->is_active ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="iyzico_active"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                            Et</label>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <x-input-label for="iyzico_api_key" value="API Key" />
                                    <x-text-input id="iyzico_api_key" name="iyzico[api_key]" type="text"
                                        class="mt-1 block w-full" :value="$iyzicoConfig['api_key'] ?? ''" />
                                </div>

                                <div class="sm:col-span-3">
                                    <x-input-label for="iyzico_secret_key" value="Secret Key" />
                                    <x-text-input id="iyzico_secret_key" name="iyzico[secret_key]" type="password"
                                        class="mt-1 block w-full"
                                        placeholder="{{ !empty($iyzicoConfig['secret_key']) ? '•••••••• (Kayıtlı - Değiştirmek için doldurun)' : '' }}" />
                                </div>

                                <div class="sm:col-span-6">
                                    <x-input-label for="iyzico_base_url" value="Base URL" />
                                    <x-text-input id="iyzico_base_url" name="iyzico[base_url]" type="text"
                                        class="mt-1 block w-full" :value="$iyzicoConfig['base_url'] ?? 'https://sandbox-api.iyzipay.com'"
                                        placeholder="https://sandbox-api.iyzipay.com" />
                                    <p class="mt-1 text-xs text-gray-500">Live: https://api.iyzipay.com</p>
                                </div>

                                <div class="sm:col-span-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="iyzico_sandbox" name="iyzico[sandbox]" value="1" {{ ($iyzicoConfig['mode'] ?? '') === 'sandbox' ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="iyzico_sandbox"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Test Modu
                                            (Sandbox)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PayTR -->
                        <div x-show="activeTab === 'paytr'" class="space-y-6" style="display: none;">
                            @php
                                $paytr = $gateways['paytr'] ?? null;
                                $paytrConfig = $paytr ? $paytr->config : [];
                            @endphp

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">PayTR
                                        Ayarları</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PayTR Sanal POS
                                        entegrasyonu.</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="button" @click="testGateway('paytr')" :disabled="testLoading"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                        <svg x-show="testLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Bağlantıyı Test Et
                                    </button>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="paytr_active" name="paytr[active]" value="1" {{ $paytr && $paytr->is_active ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="paytr_active"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                            Et</label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700 dark:text-blue-200">
                                            Callback URL (Bildirim URL): <code
                                                class="font-mono font-bold">{{ route('webhooks.paytr') }}</code><br>
                                            Bu adresi PayTR panelinize tanımlayın.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <x-input-label for="paytr_merchant_id" value="Merchant ID" />
                                    <x-text-input id="paytr_merchant_id" name="paytr[merchant_id]" type="text"
                                        class="mt-1 block w-full" :value="$paytrConfig['merchant_id'] ?? ''" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input-label for="paytr_merchant_key" value="Merchant Key" />
                                    <x-text-input id="paytr_merchant_key" name="paytr[merchant_key]" type="password"
                                        class="mt-1 block w-full"
                                        placeholder="{{ !empty($paytrConfig['merchant_key']) ? '•••••••• (Kayıtlı)' : '' }}" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input-label for="paytr_merchant_salt" value="Merchant Salt" />
                                    <x-text-input id="paytr_merchant_salt" name="paytr[merchant_salt]" type="password"
                                        class="mt-1 block w-full"
                                        placeholder="{{ !empty($paytrConfig['merchant_salt']) ? '•••••••• (Kayıtlı)' : '' }}" />
                                </div>

                                <div class="sm:col-span-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="paytr_sandbox" name="paytr[sandbox]" value="1" {{ ($paytrConfig['mode'] ?? '') === 'sandbox' ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="paytr_sandbox"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Test Modu
                                            (Sandbox)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Param -->
                        <div x-show="activeTab === 'param'" class="space-y-6" style="display: none;">
                            @php
                                $param = $gateways['param'] ?? null;
                                $paramConfig = $param ? $param->config : [];
                            @endphp

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Param
                                        Ayarları</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Param (ParamPOS)
                                        entegrasyonu.</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="button" @click="testGateway('param')" :disabled="testLoading"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                        <svg x-show="testLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Bağlantıyı Test Et
                                    </button>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="param_active" name="param[active]" value="1" {{ $param && $param->is_active ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="param_active"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                            Et</label>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <x-input-label for="param_client_code" value="Client Code" />
                                    <x-text-input id="param_client_code" name="param[client_code]" type="text"
                                        class="mt-1 block w-full" :value="$paramConfig['client_code'] ?? ''" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="param_client_username" value="Client Username" />
                                    <x-text-input id="param_client_username" name="param[client_username]" type="text"
                                        class="mt-1 block w-full" :value="$paramConfig['client_username'] ?? ''" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="param_client_password" value="Client Password" />
                                    <x-text-input id="param_client_password" name="param[client_password]"
                                        type="password" class="mt-1 block w-full"
                                        placeholder="{{ !empty($paramConfig['client_password']) ? '•••••••• (Kayıtlı)' : '' }}" />
                                </div>
                                <div class="sm:col-span-3">
                                    <x-input-label for="param_guid" value="GUID" />
                                    <x-text-input id="param_guid" name="param[guid]" type="text"
                                        class="mt-1 block w-full" :value="$paramConfig['guid'] ?? ''" />
                                </div>

                                <div class="sm:col-span-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="param_sandbox" name="param[sandbox]" value="1" {{ ($paramConfig['mode'] ?? '') === 'sandbox' ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="param_sandbox"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Test Modu
                                            (Sandbox)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div x-show="activeTab === 'paypal'" class="space-y-6" style="display: none;">
                            @php
                                $paypal = $gateways['paypal'] ?? null;
                                $paypalConfig = $paypal ? $paypal->config : [];
                            @endphp

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">PayPal
                                        Ayarları</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PayPal entegrasyonu (Yurt
                                        içi kullanımda kapalı olabilir).</p>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="paypal_active" name="paypal[active]" value="1" {{ $paypal && $paypal->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="paypal_active"
                                        class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                        Et</label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <x-input-label for="paypal_client_id" value="Client ID" />
                                    <x-text-input id="paypal_client_id" name="paypal[client_id]" type="text"
                                        class="mt-1 block w-full" :value="$paypalConfig['client_id'] ?? ''" />
                                </div>

                                <div class="sm:col-span-3">
                                    <x-input-label for="paypal_secret" value="Secret" />
                                    <x-text-input id="paypal_secret" name="paypal[secret]" type="password"
                                        class="mt-1 block w-full"
                                        placeholder="{{ !empty($paypalConfig['secret']) ? '•••••••• (Kayıtlı)' : '' }}" />
                                </div>

                                <div class="sm:col-span-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="paypal_sandbox" name="paypal[sandbox]" value="1" {{ ($paypalConfig['mode'] ?? '') === 'sandbox' ? 'checked' : '' }}
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="paypal_sandbox"
                                            class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Test Modu
                                            (Sandbox)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-primary-button>
                            {{ __('Ayarları Kaydet') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
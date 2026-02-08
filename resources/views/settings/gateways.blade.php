<x-app-layout>
    <x-page-banner title="Ödeme Altyapıları" subtitle="Stripe ve Iyzico entegrasyonlarını buradan yönetebilirsiniz." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <form action="{{ route('settings.gateways.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div x-data="{ activeTab: 'stripe' }">
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button type="button" @click="activeTab = 'stripe'"
                                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'stripe', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'stripe' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Stripe
                                </button>
                                <button type="button" @click="activeTab = 'iyzico'"
                                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'iyzico', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'iyzico' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Iyzico
                                </button>
                                <button type="button" @click="activeTab = 'paytr'"
                                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'paytr', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'paytr' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    PayTR
                                </button>
                                <button type="button" @click="activeTab = 'param'"
                                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'param', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'param' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Param
                                </button>
                                <button type="button" @click="activeTab = 'paypal'"
                                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'paypal', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'paypal' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    PayPal
                                </button>
                            </nav>
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
                                <div class="flex items-center">
                                    <input type="checkbox" id="stripe_active" name="stripe[active]" value="1" {{ $stripe && $stripe->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="stripe_active"
                                        class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                        Et</label>
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
                                        class="mt-1 block w-full" :value="$stripeConfig['secret_key'] ?? ''" />
                                </div>

                                <div class="sm:col-span-6">
                                    <x-input-label for="stripe_webhook_secret" value="Webhook Secret" />
                                    <x-text-input id="stripe_webhook_secret" name="stripe[webhook_secret]"
                                        type="password" class="mt-1 block w-full"
                                        :value="$stripeConfig['webhook_secret'] ?? ''" />
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
                                <div class="flex items-center">
                                    <input type="checkbox" id="iyzico_active" name="iyzico[active]" value="1" {{ $iyzico && $iyzico->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="iyzico_active"
                                        class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                        Et</label>
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
                                        class="mt-1 block w-full" :value="$iyzicoConfig['secret_key'] ?? ''" />
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
                                <div class="flex items-center">
                                    <input type="checkbox" id="paytr_active" name="paytr[active]" value="1" {{ $paytr && $paytr->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="paytr_active"
                                        class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                        Et</label>
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
                                        class="mt-1 block w-full" :value="$paytrConfig['merchant_key'] ?? ''" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input-label for="paytr_merchant_salt" value="Merchant Salt" />
                                    <x-text-input id="paytr_merchant_salt" name="paytr[merchant_salt]" type="password"
                                        class="mt-1 block w-full" :value="$paytrConfig['merchant_salt'] ?? ''" />
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
                                <div class="flex items-center">
                                    <input type="checkbox" id="param_active" name="param[active]" value="1" {{ $param && $param->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="param_active"
                                        class="ml-2 block text-sm text-gray-900 dark:text-gray-100 font-bold">Aktif
                                        Et</label>
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
                                        :value="$paramConfig['client_password'] ?? ''" />
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
                                        class="mt-1 block w-full" :value="$paypalConfig['secret'] ?? ''" />
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
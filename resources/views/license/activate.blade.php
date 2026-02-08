<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('MIONEX kurulumuna devam etmek için lütfen lisansınızı doğrulayın. Satın alma kodunuzu (Purchase Code) aşağıya giriniz.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('license.activate.action') }}">
        @csrf

        <!-- Client Name -->
        <div>
            <x-input-label for="client_name" :value="__('Adınız Soyadınız / Firma Adı')" />
            <x-text-input id="client_name" class="block mt-1 w-full" type="text" name="client_name"
                :value="old('client_name')" required autofocus />
            <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
        </div>

        <!-- License Key -->
        <div class="mt-4">
            <x-input-label for="license_key" :value="__('Lisans Anahtarı (Purchase Code)')" />
            <x-text-input id="license_key" class="block mt-1 w-full font-mono uppercase" type="text" name="license_key"
                :value="old('license_key')" required placeholder="MIO-XXXX-XXXX-XXXX" />
            <x-input-error :messages="$errors->get('license_key')" class="mt-2" />
            <p class="mt-2 text-xs text-gray-500">Test için anahtar: MIO-TEST-KEY-1234</p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                {{ __('Lisansı Doğrula ve Devam Et') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
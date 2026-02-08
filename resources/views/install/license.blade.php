@extends('install.layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Lisans Doğrulama</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Ürünü kullanmak için lisans anahtarınızı giriniz.</p>
    </div>

    <form method="POST" action="{{ route('install.license.verify') }}">
        @csrf

        <div class="space-y-4 mb-8">
            <!-- Client Name -->
            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri Adı /
                    Firma Adı</label>
                <div class="mt-1">
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" required
                        placeholder="Örn: Acme Inc."
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <!-- License Key -->
            <div>
                <label for="license_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lisans Anahtarı
                    / Purchase Code</label>
                <div class="mt-1">
                    <input type="text" name="license_key" id="license_key" value="{{ old('license_key') }}" required
                        placeholder="Envato Code veya Özel Lisans Anahtarı"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <p class="mt-1 text-xs text-gray-500">Envato'dan aldıysanız Purchase Code, doğrudan aldıysanız size verilen
                    anahtarı girin.</p>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('install.database') }}"
                class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                &larr; Geri
            </a>

            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                Lisansı Doğrula &rarr;
            </button>
        </div>
    </form>
@endsection
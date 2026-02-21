@extends('master.layout')

@section('content')
    <div class="mb-8">
        <a href="{{ route('master.licenses.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Listeye Dön</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Lisans Oluştur</h1>
    </div>

    <div class="max-w-2xl">
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('master.licenses.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Client Name -->
                        <div>
                            <label for="client_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri Adı / Firma
                                Ünvanı</label>
                            <input type="text" name="client_name" id="client_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                        </div>

                        <!-- Client Email -->
                        <div>
                            <label for="client_email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri E-Posta</label>
                            <input type="email" name="client_email" id="client_email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Lisans bilgileri bu adrese gönderilmez, sadece kayıt
                                içindir.</p>
                        </div>

                        <!-- License Type & Pricing -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lisans Tipi</label>
                                <select name="type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="standard">Standart Lisans (Tek Domain)</option>
                                    <option value="extended">Genişletilmiş Lisans (Çoklu Domain)</option>
                                    <option value="monthly">Aylık Kiralama</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="col-span-2">
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fiyat</label>
                                    <input type="number" step="0.01" name="price" id="price" value="0.00"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Döviz</label>
                                    <select name="currency" id="currency"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="TRY">TRY</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Cycle & Limits -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="billing_cycle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ödeme Döngüsü</label>
                                <select name="billing_cycle" id="billing_cycle"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="one-time">Tek Seferlik (Lifetime)</option>
                                    <option value="monthly">Aylık Abonelik</option>
                                    <option value="yearly">Yıllık Abonelik</option>
                                </select>
                            </div>
                            <div>
                                <label for="activation_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aktivasyon Limiti</label>
                                <input type="number" name="activation_limit" id="activation_limit" value="1" min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                        </div>

                        <!-- Expiration & Trial -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="expires_at"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lisans Bitiş
                                    Tarihi</label>
                                <input type="date" name="expires_at" id="expires_at"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <p class="mt-1 text-xs text-gray-500">Boş bırakılırsa ömür boyu geçerlidir.</p>
                            </div>
                            <div>
                                <label for="trial_ends_at"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deneme (Trial)
                                    Bitiş</label>
                                <input type="date" name="trial_ends_at" id="trial_ends_at"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <p class="mt-1 text-xs text-gray-500">Ücretli sürüme geçiş öncesi kısıtlı süre.</p>
                            </div>
                        </div>

                        <!-- Feature Flags -->
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Erişilebilir Özellikler
                                (Feature Flags)</h3>
                            <div class="grid grid-cols-2 gap-4">
                                @php
                                    $availableFeatures = [
                                        'analytics' => 'Analiz Merkezi',
                                        'crm' => 'CRM Modülü',
                                        'reports' => 'Gelişmiş Raporlama',
                                        'api_access' => 'Harici API Erişimi',
                                        'branding' => 'Beyaz Etiket (No Branding)',
                                    ];
                                @endphp
                                @foreach($availableFeatures as $key => $label)
                                    <div class="flex items-center">
                                        <input id="feature_{{ $key }}" name="features[{{ $key }}]" type="checkbox" value="1"
                                            checked class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="feature_{{ $key }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Strict Mode -->
                        <div class="flex items-start">

                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Lisansı Üret ve Kaydet
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
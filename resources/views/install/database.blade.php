@extends('install.layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Veritabanı Ayarları</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Veritabanı bağlantı bilgilerini giriniz.</p>
    </div>

    <form method="POST" action="{{ route('install.database.save') }}">
        @csrf

        <div class="space-y-4 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Host -->
                <div>
                    <label for="host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DB Host</label>
                    <div class="mt-1">
                        <input type="text" name="host" id="host" value="{{ old('host', '127.0.0.1') }}" required
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <!-- Port -->
                <div>
                    <label for="port" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DB Port</label>
                    <div class="mt-1">
                        <input type="text" name="port" id="port" value="{{ old('port', '3306') }}" required
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Database Name -->
            <div>
                <label for="database" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Veritabanı
                    Adı</label>
                <div class="mt-1">
                    <input type="text" name="database" id="database" value="{{ old('database', 'mioly') }}" required
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <p class="mt-1 text-xs text-gray-500">Mevcut değilse otomatik oluşturmayı dener.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kullanıcı
                        Adı</label>
                    <div class="mt-1">
                        <input type="text" name="username" id="username" value="{{ old('username', 'root') }}" required
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Şifre</label>
                    <div class="mt-1">
                        <input type="password" name="password" id="password"
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('install.permissions') }}"
                class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                &larr; Geri
            </a>

            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                Bağlantıyı Test Et & Kaydet &rarr;
            </button>
        </div>
    </form>
@endsection
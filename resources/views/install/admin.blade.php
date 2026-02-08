@extends('install.layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Yönetici Hesabı</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Yönetim paneline giriş için ilk yönetici hesabını oluşturun.</p>
    </div>

    <form method="POST" action="{{ route('install.admin.save') }}">
        @csrf

        <div class="space-y-4 mb-8">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Soyad</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-posta Adresi</label>
                <div class="mt-1">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Şifre</label>
                    <div class="mt-1">
                        <input type="password" name="password" id="password" required
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Şifre Tekrar</label>
                    <div class="mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="rounded-md bg-blue-50 dark:bg-blue-900/30 p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <p class="text-sm text-blue-700 dark:text-blue-300">Kaydet'e tıkladığınızda kurulum işlemi
                            başlayacak ve veritabanı tabloları oluşturulacaktır. Bu işlem birkaç saniye sürebilir.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('install.license') }}"
                class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                &larr; Geri
            </a>

            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                Kurulumu Tamamla & Koştur 🚀
            </button>
        </div>
    </form>
@endsection
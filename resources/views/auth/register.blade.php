<x-layouts.auth-split>
    <!-- Header -->
    <div class="mb-10">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
            Hesap Oluşturun
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            MIONEX dünyasına katılmak için bilgilerinizi girin.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Ad Soyad
            </label>
            <div class="mt-1">
                <input id="name" name="name" type="text" autocomplete="name" required autofocus
                    class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out"
                    value="{{ old('name') }}">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                E-posta Adresi
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out"
                    value="{{ old('email') }}">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Şifre
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autocomplete="new-password" required
                    class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Şifre Tekrar
            </label>
            <div class="mt-1">
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:-translate-y-0.5">
                Kayıt Ol
            </button>
        </div>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-900 text-gray-500">
                        Zaten üye misiniz?
                    </span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('login') }}"
                    class="w-full flex justify-center py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Giriş Yap
                </a>
            </div>
        </div>
    </form>
</x-layouts.auth-split>
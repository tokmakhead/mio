<x-layouts.auth-split>
    <!-- Header -->
    <div class="mb-10">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
            Hoş Geldiniz
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Hesabınıza erişmek için lütfen giriş yapın.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                E-posta Adresi
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required autofocus
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
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                    Beni Hatırla
                </label>
            </div>

            @if (Route::has('password.request'))
                <div class="text-sm">
                    <a href="{{ route('password.request') }}"
                        class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                        Şifremi Unuttum?
                    </a>
                </div>
            @endif
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:-translate-y-0.5">
                Giriş Yap
            </button>
        </div>
    </form>
</x-layouts.auth-split>
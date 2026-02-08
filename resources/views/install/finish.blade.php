@extends('install.layout')

@section('content')
    <div class="text-center mb-8">
        <div
            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 sm:h-20 sm:w-20">
            <svg class="h-8 w-8 text-green-600 dark:text-green-400 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Kurulum Başarılı! 🎉</h2>
        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
            MIONEX başarıyla kuruldu ve kullanıma hazır.
        </p>
    </div>

    <div class="space-y-4 mb-8">
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-sm text-gray-600 dark:text-gray-300">
            <p class="font-bold mb-2">Güvenlik Uyarısı:</p>
            <ul class="list-disc pl-5 space-y-1">
                <li><code>storage/installed</code> dosyası oluşturuldu. Bu dosya olduğu sürece kurulum tekrar
                    çalıştırılamaz.</li>
                <li>Lütfen admin şifrenizi güvenli bir yerde saklayın.</li>
            </ul>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ url('/') }}"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
            Yönetim Paneline Git &rarr;
        </a>
    </div>
@endsection
@extends('install.layout')

@section('content')
    <div class="text-center mb-8">
        <div
            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/30 sm:h-20 sm:w-20">
            <svg class="h-8 w-8 text-primary-600 dark:text-primary-400 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
        </div>
        <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Hoş Geldiniz</h2>
        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
            MIONEX kurulum sihirbazına hoş geldiniz. Kurulumu tamamlamak için lütfen adımları takip edin.
        </p>
    </div>

    <div class="space-y-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900 dark:text-white">Kolay Kurulum</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Sadece veritabanı bilgilerinizi girin, gerisini biz
                    halledelim.</p>
            </div>
        </div>

        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900 dark:text-white">Güvenli</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Lisans doğrulama ve güvenli yönetici hesabı oluşturma.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('install.requirements') }}"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
            Kuruluma Başla &rarr;
        </a>
    </div>
@endsection
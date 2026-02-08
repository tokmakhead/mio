@extends('install.layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dosya İzinleri</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Uygulamanın yazma iznine ihtiyaç duyduğu klasörler.</p>
    </div>

    <div class="space-y-3 mb-8">
        @foreach($permissions as $path => $isWritable)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 font-mono">{{ $path }}</span>
                @if($isWritable)
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                        <svg class="-ml-0.5 mr-1.5 h-3 w-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Yazılabilir
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                        <svg class="-ml-0.5 mr-1.5 h-3 w-3 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Yazılamaz
                    </span>
                @endif
            </div>
        @endforeach
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('install.requirements') }}"
            class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
            &larr; Geri
        </a>

        @if($allMet)
            <a href="{{ route('install.database') }}"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                Veritabanı Ayarları &rarr;
            </a>
        @else
            <a href="{{ route('install.permissions') }}"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                Tekrar Kontrol Et ↻
            </a>
        @endif
    </div>
@endsection
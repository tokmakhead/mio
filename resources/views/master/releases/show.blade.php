@extends('master.layout')

@section('content')
    <div class="mb-6">
        <a href="{{ route('master.releases.index') }}"
            class="flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Sürüm Listesine Dön
        </a>
    </div>

    <div
        class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <!-- Header -->
        <div
            class="bg-gray-50 dark:bg-gray-700/50 px-6 py-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    v{{ $release->version }}
                    @if($release->is_critical)
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-800">
                            Kritik
                        </span>
                    @else
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-200 dark:border-green-800">
                            Standart
                        </span>
                    @endif
                </h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Yayınlanma: {{ $release->published_at }}
                    <span class="mx-2">•</span>
                    {{ \Carbon\Carbon::parse($release->published_at)->diffForHumans() }}
                </p>
            </div>
            <div class="flex gap-3">
                {{-- Actions can be added here like Edit/Delete --}}
            </div>
        </div>

        <div class="p-8">
            <!-- File Info -->
            <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Dosya Yolu
                </h3>
                <div class="flex items-center justify-between">
                    <code
                        class="text-sm font-mono text-purple-600 dark:text-purple-400 bg-white dark:bg-gray-800 px-3 py-2 rounded border border-gray-200 dark:border-gray-700 block w-full">
                            {{ $release->file_path }}
                        </code>
                    <a href="#"
                        class="ml-4 text-sm text-gray-600 dark:text-gray-400 hover:text-purple-600 underline">İndir</a>
                </div>
            </div>

            <!-- Release Notes -->
            <div>
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Sürüm Notları</h3>
                <div class="prose prose-purple max-w-none dark:prose-invert">
                    {!! $release->release_notes !!}
                </div>
            </div>
        </div>
    </div>
@endsection
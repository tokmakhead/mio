@extends('master.layout')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sürüm Yönetimi</h1>
            <p class="text-gray-500 dark:text-gray-400">Yazılım güncellemelerini ve sürüm paketlerini yönetin.</p>
        </div>
        <a href="{{ route('master.releases.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Yeni Sürüm Yayınla
        </a>
    </div>

    <!-- Release List -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Versiyon</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Yayınlanma Tarihi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tür</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dosya</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notlar</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">İşlemler</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($releases as $release)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $release->version }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $release->published_at }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($release->published_at)->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($release->is_critical)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Kritik Güncelleme
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Standart
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                     <div class="text-sm text-gray-500 font-mono">{{ Str::limit($release->file_path, 20) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                     <div class="text-sm text-gray-500 max-w-xs truncate">{{ strip_tags($release->release_notes) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('master.releases.show', $release->id) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-900">Detay</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Henüz yayınlanmış bir sürüm paketi yok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

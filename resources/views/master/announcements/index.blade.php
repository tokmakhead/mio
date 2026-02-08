@extends('master.layout')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Duyurular</h1>
            <p class="text-gray-500 dark:text-gray-400">Tüm panellere anlık bildirim gönderin.</p>
        </div>
        <a href="{{ route('master.announcements.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                </path>
            </svg>
            Yeni Duyuru Ekle
        </a>
    </div>

    <!-- Announcements List -->
    <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Başlık</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tip</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tarih Aralığı</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Durum</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">İşlemler</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($announcements as $announcement)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $announcement->title }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs">
                                        {{ Str::limit($announcement->message, 50) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $colors = [
                                            'info' => 'bg-blue-100 text-blue-800',
                                            'warning' => 'bg-yellow-100 text-yellow-800',
                                            'danger' => 'bg-red-100 text-red-800',
                                            'success' => 'bg-green-100 text-green-800',
                                        ];
                                        $labels = [
                                            'info' => 'Bilgi',
                                            'warning' => 'Uyarı',
                                            'danger' => 'Kritik',
                                            'success' => 'Başarılı',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colors[$announcement->type] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $labels[$announcement->type] ?? ucfirst($announcement->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $announcement->start_date ? \Carbon\Carbon::parse($announcement->start_date)->format('d.m.Y') : 'Hemen' }}
                                        -
                                        {{ $announcement->end_date ? \Carbon\Carbon::parse($announcement->end_date)->format('d.m.Y') : 'Süresiz' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="flex items-center">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full {{ $announcement->created_at->diffInDays(now()) < 7 ? 'bg-green-400' : 'bg-gray-300' }} mr-2"></span>
                                        <span
                                            class="text-sm text-gray-500">{{ $announcement->created_at->diffForHumans() }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        onclick="openModal('{{ route('master.announcements.destroy', $announcement->id) }}', 'Duyuruyu Sil', 'Bu duyuruyu kalıcı olarak silmek istediğinize emin misiniz?', 'DELETE')"
                                        class="text-red-600 hover:text-red-900">
                                        Kaldır
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Henüz hiç duyuru yapılmamış.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
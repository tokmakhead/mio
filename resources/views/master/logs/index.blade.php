@extends('master.layout')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Güvenlik Günlükleri</h1>
        <p class="text-gray-500 dark:text-gray-400">Master Panel üzerinde gerçekleştirilen tüm yönetimsel işlemler.</p>
    </div>

    <!-- Logs List -->
    <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tarih</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Yönetici</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                İşlem</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Açıklama</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                IP / Cihaz</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->created_at->format('d.m.Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $log->user->name ?? 'Sistem' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $badges = [
                                                            'license_created' => 'bg-green-100 text-green-800',
                                                            'license_suspended' => 'bg-red-100 text-red-800',
                                                            'release_published' => 'bg-blue-100 text-blue-800',
                                                            'announcement_created' => 'bg-purple-100 text-purple-800',
                                                            'announcement_deleted' => 'bg-gray-100 text-gray-800',
                                                        ];
                                                    @endphp
                             <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badges[$log->action] ?? 'bg-gray-100 text-gray-600' }}">
                                                        {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900 dark:text-white">{{ $log->description }}</div>
                                                    @if($log->subject)
                                                        <div class="text-xs text-indigo-500 font-mono mt-1">Ref:
                                                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    <div>{{ $log->ip_address }}</div>
                                                    <div class="truncate max-w-xs" title="{{ $log->user_agent }}">
                                                        {{ Str::limit($log->user_agent, 40) }}</div>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Henüz günlük kaydı bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
<x-app-layout>
    <x-page-banner title="E-posta Geçmişi" subtitle="Sistem tarafından gönderilen e-postaların kayıtları." />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('settings.smtp') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    SMTP Ayarlarına Dön
                </a>
                <form action="{{ route('settings.logs.clear') }}" method="POST"
                    onsubmit="return confirm('Tüm e-posta kayıtlarını silmek istediğinize emin misiniz?');">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition">
                        Logları Temizle
                    </button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Durum</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Alıcı</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Konu</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tarih</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($logs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->status == 'sent')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Gönderildi</span>
                                            @elseif($log->status == 'queued')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Kuyrukta</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Hata</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $log->to }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $log->subject }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $log->created_at ? $log->created_at->format('d.m.Y H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="showLogDetails('{{ $log->id }}')"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Detay</button>
                                        </td>
                                    </tr>
                                    <!-- Log Details Modal (Hidden by default) -->
                                    <dialog id="log_modal_{{ $log->id }}"
                                        class="p-6 rounded-lg shadow-xl dark:bg-gray-800 dark:text-white backdrop:bg-gray-900/50 w-full max-w-2xl">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-bold">Log Detayı #{{ $log->id }}</h3>
                                            <button onclick="document.getElementById('log_modal_{{ $log->id }}').close()"
                                                class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
                                        </div>
                                        <div class="space-y-4">
                                            @if($log->error_message)
                                                <div
                                                    class="bg-red-50 dark:bg-red-900/20 p-4 rounded-md border border-red-200 dark:border-red-800">
                                                    <h4 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-1">Hata
                                                        Mesajı:</h4>
                                                    <pre
                                                        class="text-xs text-red-700 dark:text-red-400 whitespace-pre-wrap">{{ $log->error_message }}</pre>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                                    E-posta İçeriği:</h4>
                                                <div
                                                    class="bg-gray-50 dark:bg-gray-900 p-4 rounded-md border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto">
                                                    {!! $log->body !!}
                                                </div>
                                            </div>
                                        </div>
                                    </dialog>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            Henüz gönderilen e-posta kaydı bulunmuyor.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLogDetails(id) {
            document.getElementById('log_modal_' + id).showModal();
        }
    </script>
</x-app-layout>
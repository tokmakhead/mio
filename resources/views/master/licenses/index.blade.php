@extends('master.layout')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lisanslar</h1>
            <p class="text-gray-500 dark:text-gray-400">Tüm müşteri lisanslarını yönetin.</p>
        </div>
        <a href="{{ route('master.licenses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Yeni Lisans Üret
        </a>
    </div>

    <!-- License List -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kod (Lisans Anahtarı)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Müşteri</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Domain / IP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tür</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durum</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">İşlemler</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($licenses as $license)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono font-bold text-gray-900 dark:text-white">{{ $license->code }}</div>
                                    <div class="text-xs text-gray-500">Created {{ $license->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $license->client_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $license->client_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $license->domain ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $license->ip_address ?? 'Not Activated' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ ucfirst($license->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($license->status === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ ucfirst($license->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($license->status !== 'cancelled')
                                        <button onclick="openModal('{{ route('master.licenses.cancel', $license->id) }}', 'Lisans İptali', 'Bu lisansı iptal etmek üzeresiniz. Müşteri artık sisteme erişemeyecek. Emin misiniz?', 'POST')" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-3 py-1 rounded-full text-xs">
                                            Lisansı İptal Et
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs italic">İptal Edildi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Henüz hiç lisans oluşturulmamış.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $licenses->links() }}
            </div>
        </div>
    </div>
@endsection

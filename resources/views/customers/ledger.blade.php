<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cari Hesap Ekstresi') }} - {{ $customer->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <p class="text-lg font-semibold">
                            Toplam Bakiye: 
                            <span class="{{ $customer->balance > 0 ? 'text-red-600' : ($customer->balance < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                {{ number_format(abs($customer->balance), 2) }} {{ $entries->first()->currency ?? 'TRY' }} 
                                {{ $customer->balance > 0 ? '(Borçlu)' : ($customer->balance < 0 ? '(Alacaklı)' : '-') }}
                            </span>
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tarih
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        İşlem Türü
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Açıklama
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Borç
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Alacak
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Bakiye
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($entries as $entry)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $entry->occurred_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if ($entry->type === 'debit')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    Borç (Fatura)
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    Alacak (Ödeme)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $entry->description }}
                                            @if ($entry->ref_type === 'App\Models\Invoice')
                                                <a href="{{ route('invoices.show', $entry->ref_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline ms-2 text-xs">Görüntüle</a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">
                                            @if ($entry->type === 'debit')
                                                {{ number_format($entry->amount, 2) }} {{ $entry->currency }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">
                                            @if ($entry->type === 'credit')
                                                {{ number_format($entry->amount, 2) }} {{ $entry->currency }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold {{ $entry->balance > 0 ? 'text-red-600' : ($entry->balance < 0 ? 'text-green-600' : 'text-gray-900') }}">
                                            {{ number_format(abs($entry->balance), 2) }} {{ $entry->currency }}
                                            {{ $entry->balance > 0 ? '(B)' : ($entry->balance < 0 ? '(A)' : '') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Henüz kayıt bulunmuyor.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

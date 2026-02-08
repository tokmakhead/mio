<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ödeme Ekle') }} - Fatura #{{ $invoice->number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('invoices.payments.store', $invoice) }}">
                        @csrf

                        <!-- Amount -->
                        <div>
                            <x-input-label for="amount" :value="__('Tutar')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount"
                                :value="old('amount', $invoice->remaining_amount)" required autofocus />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Kalan Tutar: {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}
                            </p>
                        </div>

                        <!-- Method -->
                        <div class="mt-4">
                            <x-input-label for="method" :value="__('Ödeme Yöntemi')" />
                            <select id="method" name="method"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Nakit</option>
                                <option value="bank" {{ old('method') == 'bank' ? 'selected' : '' }}>Banka Transferi / EFT
                                </option>
                                <option value="card" {{ old('method') == 'card' ? 'selected' : '' }}>Kredi Kartı</option>
                                <option value="check" {{ old('method') == 'check' ? 'selected' : '' }}>Çek / Senet
                                </option>
                                <option value="other" {{ old('method') == 'other' ? 'selected' : '' }}>Diğer</option>
                            </select>
                            <x-input-error :messages="$errors->get('method')" class="mt-2" />
                        </div>

                        <!-- Paid At -->
                        <div class="mt-4">
                            <x-input-label for="paid_at" :value="__('Ödeme Tarihi')" />
                            <x-text-input id="paid_at" class="block mt-1 w-full" type="date" name="paid_at"
                                :value="old('paid_at', now()->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('paid_at')" class="mt-2" />
                        </div>

                        <!-- Note -->
                        <div class="mt-4">
                            <x-input-label for="note" :value="__('Not')" />
                            <textarea id="note" name="note"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="3">{{ old('note') }}</textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('İptal') }}
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Ödemeyi Kaydet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="{{ $customer->name }}"
        subtitle="{{ $customer->type == 'individual' ? 'Bireysel Müşteri' : 'Kurumsal Müşteri' }}"
        metric="{{ $customer->status == 'active' ? 'Aktif' : 'Pasif' }}" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mb-6">
                <a href="{{ route('customers.edit', $customer) }}"
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Düzenle</span>
                </a>

                <x-delete-modal title="Müşteriyi Sil"
                    message="Bu müşteriyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                    :action="route('customers.destroy', $customer)"
                    class="px-4 py-2 bg-danger-600 hover:bg-danger-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    <span>Sil</span>
                </x-delete-modal>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Information Card -->
                <div class="lg:col-span-1">
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Müşteri Bilgileri</h3>

                        <!-- Avatar -->
                        <div class="flex justify-center mb-6">
                            <div
                                class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center text-white font-bold text-3xl">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                        </div>

                        <!-- Info List -->
                        <div class="space-y-4">
                            <!-- Type -->
                            <div>
                                <label
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tür</label>
                                <div class="mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $customer->type == 'individual' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' }}">
                                        {{ $customer->type == 'individual' ? 'Bireysel' : 'Kurumsal' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Email -->
                            @if($customer->email)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">E-posta</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $customer->email }}</p>
                                </div>
                            @endif

                            <!-- Phone -->
                            @if($customer->phone)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telefon</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $customer->phone }}</p>
                                </div>
                            @endif

                            <!-- Mobile Phone -->
                            @if($customer->mobile_phone)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mobil</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $customer->mobile_phone }}</p>
                                </div>
                            @endif

                            <!-- Website -->
                            @if($customer->website)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Website</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a href="{{ $customer->website }}" target="_blank"
                                            class="text-primary-600 hover:text-primary-700 dark:text-primary-400">{{ $customer->website }}</a>
                                    </p>
                                </div>
                            @endif

                            <!-- Tax/ID Number -->
                            @if($customer->tax_or_identity_number)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Vergi/TC
                                        No</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $customer->tax_or_identity_number }}
                                    </p>
                                </div>
                            @endif

                            <!-- Address -->
                            @if($customer->full_address)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Adres</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $customer->full_address }}</p>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($customer->notes)
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notlar</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $customer->notes }}</p>
                                </div>
                            @endif

                            <!-- Created At -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kayıt
                                    Tarihi</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $customer->created_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Services and Invoices -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Services Card -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hizmetler</h3>
                        @if($customer->services->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr
                                            class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Hizmet</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Tür</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Durum</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Vade</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-right">Tutar
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($customer->services as $service)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $service->name }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ \App\Models\Service::getTypeBadgeColor($service->type) }}">
                                                        {{ \App\Models\Service::getTypeLabel($service->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span
                                                            class="w-2 h-2 rounded-full {{ \App\Models\Service::getStatusDotColor($service->status) }}"></span>
                                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                                            {{ \App\Models\Service::getStatusLabel($service->status) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $service->end_date->format('d.m.Y') }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white text-right">
                                                    {{ number_format($service->price, 2) }} {{ $service->currency }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <x-empty-state title="Henüz hizmet yok"
                                message="Bu müşteriye ait henüz bir hizmet kaydı bulunmuyor." cta="Hizmet Ekle"
                                href="{{ route('services.create') }}" />
                        @endif
                    </x-card>

                    <!-- Invoices Card -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Fatura Geçmişi</h3>
                        @if($customer->invoices->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr
                                            class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Fatura No</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Durum</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Tarih</th>
                                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase text-right">
                                                Toplam</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($customer->invoices as $invoice)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                                <td class="px-4 py-3 text-sm font-mono font-bold text-gray-900 dark:text-white">
                                                    <a href="{{ route('invoices.show', $invoice) }}"
                                                        class="hover:text-primary-600 underline">
                                                        {{ $invoice->number }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ \App\Models\Invoice::getStatusColor($invoice->status) }}">
                                                        {{ \App\Models\Invoice::getStatusLabel($invoice->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $invoice->issue_date->format('d.m.Y') }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white text-right">
                                                    {{ number_format($invoice->grand_total, 2) }} {{ $invoice->currency }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <x-empty-state title="Henüz fatura yok"
                                message="Bu müşteriye ait henüz bir fatura kaydı bulunmuyor." cta="Fatura Oluştur"
                                href="{{ route('invoices.create') }}" />
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
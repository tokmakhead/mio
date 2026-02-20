<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Hizmet Yönetimi"
        subtitle="Aktif hizmetlerinizi, domain ve hosting sürelerini buradan takip edebilirsiniz.">
        <x-slot name="metric">
            @foreach($mrrByCurrency as $currency => $amount)
                <span class="mr-2">{{ number_format($amount, 2) }} {{ $currency }}</span>
            @endforeach
            MRR
        </x-slot>
    </x-page-banner>

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-kpi-card title="Toplam Hizmet" value="{{ $totalServices }}" tone="primary"
                    icon='<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' />

                <x-kpi-card title="Domain Sayısı" value="{{ $domainCount }}" tone="success"
                    icon='<svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>' />

                <x-kpi-card title="Hosting Sayısı" value="{{ $hostingCount }}" tone="warning"
                    icon='<svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>' />

                <x-kpi-card title="Aylık Gelir (MRR)" 
                    value="{{ $mrrByCurrency['TRY'] ?? 0 > 0 ? number_format($mrrByCurrency['TRY'], 2) . ' ₺' : '' }}" 
                    tone="danger"
                    sub-value="{{ $mrrByCurrency->except('TRY')->map(fn($v, $k) => number_format($v, 2) . ' ' . $k)->join(' | ') }}"
                    icon='<svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' />
            </div>

            <!-- Filter Card -->
            <x-card class="mb-6">

                <form method="GET" action="{{ route('services.index') }}" class="grid grid-cols-1 md:grid-cols-10 gap-4 items-end">
                    <!-- Search -->
                    <div class="md:col-span-3">
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arama</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Ad, kod..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Durum</label>
                        <select name="status" id="status"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Tümü</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Askıda
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal
                            </option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Süresi Dolmuş
                            </option>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="md:col-span-2">
                        <label for="type"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tür</label>
                        <select name="type" id="type"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Tümü</option>
                            <option value="hosting" {{ request('type') == 'hosting' ? 'selected' : '' }}>Hosting</option>
                            <option value="domain" {{ request('type') == 'domain' ? 'selected' : '' }}>Domain</option>
                            <option value="ssl" {{ request('type') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>E-posta</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Diğer</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="md:col-span-3 flex space-x-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 shadow-sm font-medium">
                            Sorgula
                        </button>
                        <a href="{{ route('services.index') }}"
                            class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200 text-center font-medium">
                            Sıfırla
                        </a>
                    </div>
                </form>
            </x-card>

            <!-- Services Table or Empty State -->
            @if($services->count() > 0)
                <x-card>
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Hizmetler</h3>
                        <a href="{{ route('services.create') }}"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            <span>Yeni Hizmet Ekle</span>
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Hizmet</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Net Kâr</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Kod</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tür</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Müşteri</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Sağlayıcı</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Dönem</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Durum</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Vade</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($services as $service)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                        <!-- Service Name + Price -->
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-lg {{ $service->type == 'domain' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' : ($service->type == 'hosting' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400') }} flex items-center justify-center flex-shrink-0">
                                                    @if($service->type == 'domain')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                                    @elseif($service->type == 'hosting')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $service->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ number_format($service->price, 2) }} {{ $service->currency }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Net Profit -->
                                        <td class="px-4 py-4">
                                            @if($service->buying_price)
                                                <div class="flex flex-col">
                                                    @php
                                                        $isSameCurrency = $service->currency === ($service->buying_currency ?? 'TRY');
                                                        $profit = $isSameCurrency ? $service->price - $service->buying_price : null;
                                                    @endphp

                                                    @if($isSameCurrency)
                                                        <div class="flex flex-col">
                                                            <span class="text-sm font-bold {{ $profit >= 0 ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}">
                                                                {{ $profit >= 0 ? '+' : '' }}{{ number_format($profit, 2) }} {{ $service->currency }}
                                                            </span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-tighter">Net Kâr</span>
                                                        </div>
                                                    @else
                                                        <div class="flex flex-col">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ number_format($service->price, 2) }} {{ $service->currency }}
                                                            </span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-tighter">
                                                                Maliyet: {{ number_format($service->buying_price, 2) }} {{ $service->buying_currency ?? 'TRY' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>

                                        <!-- Identifier Code Pill -->
                                        <td class="px-4 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-mono font-medium rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                                {{ $service->identifier_code }}
                                            </span>
                                        </td>

                                        <!-- Type Badge -->
                                        <td class="px-4 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\Service::getTypeBadgeColor($service->type) }}">
                                                {{ \App\Models\Service::getTypeLabel($service->type) }}
                                            </span>
                                        </td>

                                        <!-- Customer -->
                                        <td class="px-4 py-4">
                                            @if($service->customer && $service->customer->id)
                                                <a href="{{ route('customers.show', $service->customer->id) }}"
                                                    class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                    {{ $service->customer->name }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>

                                        <!-- Provider -->
                                        <td class="px-4 py-4">
                                            @if($service->provider && $service->provider->id)
                                                <a href="{{ route('providers.show', $service->provider->id) }}"
                                                    class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                    {{ $service->provider->name }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>

                                        <!-- Cycle -->
                                        <td class="px-4 py-4">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ \App\Models\Service::getCycleLabel($service->cycle) }}
                                            </span>
                                        </td>

                                        <!-- Status Dot -->
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2 whitespace-nowrap">
                                                <span
                                                    class="w-2 h-2 rounded-full flex-shrink-0 {{ \App\Models\Service::getStatusDotColor($service->status) }}"></span>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ \App\Models\Service::getStatusLabel($service->status) }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Expiry Date with Color -->
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $service->end_date ? $service->end_date->format('d.m.Y') : '-' }}
                                                </span>
                                                @if($service->days_until_expiry >= 0)
                                                    <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $service->days_until_expiry <= 30 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                                        {{ $service->days_until_expiry }} gün kaldı
                                                    </span>
                                                @else
                                                    <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400">
                                                        Süresi doldu
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('services.show', $service) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50/50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-indigo-100 dark:hover:bg-indigo-900/40"
                                                    title="Görüntüle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('services.edit', $service) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50/50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-emerald-100 dark:hover:bg-emerald-900/40"
                                                    title="Düzenle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>
                                                <x-delete-modal title="Hizmeti Sil"
                                                    message="Bu hizmeti silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                                    :action="route('services.destroy', $service)" title="Sil" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $services->links() }}
                    </div>
                </x-card>
            @else
                <x-card>
                    <x-empty-state title="Hizmet Kaydı Bulunmuyor"
                        message="Sistemde henüz kayıtlı hizmet bulunmamaktadır. Yeni bir hizmet ekleyerek başlayabilirsiniz."
                        cta="Yeni Hizmet Ekle" href="{{ route('services.create') }}" />
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
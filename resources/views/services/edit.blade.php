<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Hizmet Düzenle" subtitle="{{ $service->name }} ({{ $service->identifier_code }})" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('services.update', $service) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Customer & Provider -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Customer -->
                        <div>
                            <label for="customer_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Müşteri *
                            </label>
                            <select name="customer_id" id="customer_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('customer_id') border-danger-500 @enderror">
                                <option value="">Müşteri Seçin</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $service->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Provider -->
                        <div>
                            <label for="provider_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Sağlayıcı *
                            </label>
                            <select name="provider_id" id="provider_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('provider_id') border-danger-500 @enderror">
                                <option value="">Sağlayıcı Seçin</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" {{ old('provider_id', $service->provider_id) == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provider_id')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Type & Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Hizmet Türü *
                            </label>
                            <select name="type" id="type" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                <option value="hosting" {{ old('type', $service->type) == 'hosting' ? 'selected' : '' }}>
                                    Hosting</option>
                                <option value="domain" {{ old('type', $service->type) == 'domain' ? 'selected' : '' }}>
                                    Domain</option>
                                <option value="ssl" {{ old('type', $service->type) == 'ssl' ? 'selected' : '' }}>SSL
                                </option>
                                <option value="email" {{ old('type', $service->type) == 'email' ? 'selected' : '' }}>
                                    E-posta</option>
                                <option value="other" {{ old('type', $service->type) == 'other' ? 'selected' : '' }}>Diğer
                                </option>
                            </select>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Hizmet Adı *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('name') border-danger-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Identifier Code -->
                    <div>
                        <label for="identifier_code"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanımlayıcı Kod *
                        </label>
                        <input type="text" name="identifier_code" id="identifier_code"
                            value="{{ old('identifier_code', $service->identifier_code) }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('identifier_code') border-danger-500 @enderror">
                        @error('identifier_code')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cycle, Payment Type, Status -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Cycle -->
                        <div>
                            <label for="cycle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Dönem *
                            </label>
                            <select name="cycle" id="cycle" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                <option value="monthly" {{ old('cycle', $service->cycle) == 'monthly' ? 'selected' : '' }}>Aylık</option>
                                <option value="quarterly" {{ old('cycle', $service->cycle) == 'quarterly' ? 'selected' : '' }}>3 Aylık</option>
                                <option value="yearly" {{ old('cycle', $service->cycle) == 'yearly' ? 'selected' : '' }}>
                                    Yıllık</option>
                                <option value="biennial" {{ old('cycle', $service->cycle) == 'biennial' ? 'selected' : '' }}>2 Yıllık</option>
                                <option value="custom" {{ old('cycle', $service->cycle) == 'custom' ? 'selected' : '' }}>
                                    Özel</option>
                            </select>
                        </div>

                        <!-- Payment Type -->
                        <div>
                            <label for="payment_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Ödeme Tipi *
                            </label>
                            <select name="payment_type" id="payment_type" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                <option value="installment" {{ old('payment_type', $service->payment_type) == 'installment' ? 'selected' : '' }}>Taksitli</option>
                                <option value="upfront" {{ old('payment_type', $service->payment_type) == 'upfront' ? 'selected' : '' }}>Peşin</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Durum *
                            </label>
                            <select name="status" id="status" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="suspended" {{ old('status', $service->status) == 'suspended' ? 'selected' : '' }}>Askıda</option>
                                <option value="cancelled" {{ old('status', $service->status) == 'cancelled' ? 'selected' : '' }}>İptal</option>
                                <option value="expired" {{ old('status', $service->status) == 'expired' ? 'selected' : '' }}>Süresi Dolmuş</option>
                            </select>
                        </div>
                    </div>

                    <!-- Currency & Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Currency -->
                        <div>
                            <label for="currency"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Para Birimi *
                            </label>
                            <select name="currency" id="currency" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                <option value="TRY" {{ old('currency', $service->currency) == 'TRY' ? 'selected' : '' }}>
                                    TRY (₺)</option>
                                <option value="USD" {{ old('currency', $service->currency) == 'USD' ? 'selected' : '' }}>
                                    USD ($)</option>
                                <option value="EUR" {{ old('currency', $service->currency) == 'EUR' ? 'selected' : '' }}>
                                    EUR (€)</option>
                                <option value="GBP" {{ old('currency', $service->currency) == 'GBP' ? 'selected' : '' }}>
                                    GBP (£)</option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Fiyat *
                            </label>
                            <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}"
                                required step="0.01" min="0"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('price') border-danger-500 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Start Date & End Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Başlangıç Tarihi *
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $service->start_date->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('start_date') border-danger-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Bitiş Tarihi *
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $service->end_date->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('end_date') border-danger-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex items-center justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('services.index') }}"
                            class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                            İptal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200">
                            Güncelle
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
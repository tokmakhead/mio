<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Yeni Hizmet" subtitle="Yeni bir hizmet ekleyin" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('services.store') }}" method="POST" class="space-y-6">
                    @csrf

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
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                                    <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
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
                                <option value="">Tür Seçin</option>
                                <option value="hosting" {{ old('type') == 'hosting' ? 'selected' : '' }}>Hosting</option>
                                <option value="domain" {{ old('type') == 'domain' ? 'selected' : '' }}>Domain</option>
                                <option value="ssl" {{ old('type') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>E-posta</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Diğer</option>
                            </select>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Hizmet Adı *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                            Tanımlayıcı Kod <span class="text-gray-400 font-normal text-xs">(Boş bırakırsanız otomatik
                                oluşturulur)</span>
                        </label>
                        <input type="text" name="identifier_code" id="identifier_code"
                            value="{{ old('identifier_code') }}" placeholder="örn: HST-2024001 (Opsiyonel)"
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
                                <option value="monthly" {{ old('cycle') == 'monthly' ? 'selected' : '' }}>Aylık</option>
                                <option value="quarterly" {{ old('cycle') == 'quarterly' ? 'selected' : '' }}>3 Aylık
                                </option>
                                <option value="yearly" {{ old('cycle') == 'yearly' ? 'selected' : '' }}>Yıllık</option>
                                <option value="biennial" {{ old('cycle') == 'biennial' ? 'selected' : '' }}>2 Yıllık
                                </option>
                                <option value="custom" {{ old('cycle') == 'custom' ? 'selected' : '' }}>Özel</option>
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
                                <option value="installment" {{ old('payment_type') == 'installment' ? 'selected' : '' }}>
                                    Taksitli</option>
                                <option value="upfront" {{ old('payment_type') == 'upfront' ? 'selected' : '' }}>Peşin
                                </option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Durum *
                            </label>
                            <select name="status" id="status" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Askıda
                                </option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>İptal
                                </option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Süresi Dolmuş
                                </option>
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
                                <option value="TRY" {{ old('currency', 'TRY') == 'TRY' ? 'selected' : '' }}>TRY (₺)
                                </option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Satış Fiyatı *
                            </label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required step="0.01"
                                min="0"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('price') border-danger-500 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buying Price -->
                        <div>
                            <label for="buying_price"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Alış Fiyatı (Maliyet)
                            </label>
                            <input type="number" name="buying_price" id="buying_price" value="{{ old('buying_price') }}"
                                step="0.01" min="0"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('buying_price') border-danger-500 @enderror">
                            @error('buying_price')
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
                                value="{{ old('start_date', now()->format('Y-m-d')) }}" required
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
                                value="{{ old('end_date', now()->addYear()->format('Y-m-d')) }}" required
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
                            Kaydet
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
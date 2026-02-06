<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Müşteri Düzenle" subtitle="{{ $customer->name }}" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Customer Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Müşteri Türü
                            *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all {{ old('type', $customer->type) == 'individual' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-primary-300' }}">
                                <input type="radio" name="type" value="individual" class="sr-only" {{ old('type', $customer->type) == 'individual' ? 'checked' : '' }}
                                    onchange="this.form.querySelectorAll('label').forEach(l => l.classList.remove('border-primary-600', 'bg-primary-50', 'dark:bg-primary-900/20')); this.parentElement.classList.add('border-primary-600', 'bg-primary-50', 'dark:bg-primary-900/20');">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">Bireysel</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Şahıs müşteri</div>
                                    </div>
                                </div>
                            </label>

                            <label
                                class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all {{ old('type', $customer->type) == 'corporate' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-primary-300' }}">
                                <input type="radio" name="type" value="corporate" class="sr-only" {{ old('type', $customer->type) == 'corporate' ? 'checked' : '' }}
                                    onchange="this.form.querySelectorAll('label').forEach(l => l.classList.remove('border-primary-600', 'bg-primary-50', 'dark:bg-primary-900/20')); this.parentElement.classList.add('border-primary-600', 'bg-primary-50', 'dark:bg-primary-900/20');">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">Kurumsal</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Firma müşteri</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Temel Bilgiler</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ad Soyad /
                                    Firma Adı *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('name') border-danger-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-posta</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('email') border-danger-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                                    placeholder="0212 123 45 67"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Mobile Phone -->
                            <div>
                                <label for="mobile_phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mobil
                                    Telefon</label>
                                <input type="text" name="mobile_phone" id="mobile_phone"
                                    value="{{ old('mobile_phone', $customer->mobile_phone) }}"
                                    placeholder="0532 123 45 67"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                                <input type="text" name="website" id="website"
                                    value="{{ old('website', $customer->website) }}" placeholder="www.example.com"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Tax/ID Number -->
                            <div class="md:col-span-2">
                                <label for="tax_or_identity_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vergi / TC
                                    Kimlik No</label>
                                <input type="text" name="tax_or_identity_number" id="tax_or_identity_number"
                                    value="{{ old('tax_or_identity_number', $customer->tax_or_identity_number) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Adres Bilgileri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adres</label>
                                <textarea name="address" id="address" rows="3"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">{{ old('address', $customer->address) }}</textarea>
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Şehir</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $customer->city) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- District -->
                            <div>
                                <label for="district"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İlçe</label>
                                <input type="text" name="district" id="district"
                                    value="{{ old('district', $customer->district) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label for="postal_code"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Posta
                                    Kodu</label>
                                <input type="text" name="postal_code" id="postal_code"
                                    value="{{ old('postal_code', $customer->postal_code) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ülke</label>
                                <input type="text" name="country" id="country"
                                    value="{{ old('country', $customer->country) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>
                    </div>

                    <!-- Other Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Diğer Bilgiler</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Durum
                                    *</label>
                                <select name="status" id="status" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                                    <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                </select>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notlar</label>
                                <textarea name="notes" id="notes" rows="4"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                                    placeholder="Müşteri hakkında notlar...">{{ old('notes', $customer->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex items-center justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('customers.index') }}"
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
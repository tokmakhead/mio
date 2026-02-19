<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Yeni Sağlayıcı" subtitle="Yeni bir sağlayıcı ekleyin" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('providers.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Sağlayıcı Adı *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('name') border-danger-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Corporate Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Kurumsal Bilgiler</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tax Office -->
                            <div>
                                <label for="tax_office"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Vergi Dairesi
                                </label>
                                <input type="text" name="tax_office" id="tax_office" value="{{ old('tax_office') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Tax Number -->
                            <div>
                                <label for="tax_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Vergi Numarası
                                </label>
                                <input type="text" name="tax_number" id="tax_number" value="{{ old('tax_number') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Adres
                                </label>
                                <textarea name="address" id="address" rows="2"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Types (Checkbox Group) -->
                    <div x-data="{ 
                        selectedTypes: {{ json_encode(old('types', [])) }},
                        toggle(value) {
                            if (this.selectedTypes.includes(value)) {
                                this.selectedTypes = this.selectedTypes.filter(t => t !== value);
                            } else {
                                this.selectedTypes.push(value);
                            }
                        }
                    }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Hizmet Türleri *
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <!-- Standard Types -->
                            @foreach(\App\Models\Provider::getAvailableTypes() as $type => $config)
                                <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="selectedTypes.includes('{{ $type }}') ? 'border-{{ $config['color'] }}-500 bg-{{ $config['color'] }}-50 dark:bg-{{ $config['color'] }}-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-{{ $config['color'] }}-300'">
                                    <input type="checkbox" name="types[]" value="{{ $type }}" @click="toggle('{{ $type }}')"
                                        :checked="selectedTypes.includes('{{ $type }}')"
                                        class="w-4 h-4 text-{{ $config['color'] }}-600 rounded focus:ring-{{ $config['color'] }}-500 dark:focus:ring-{{ $config['color'] }}-600 dark:ring-offset-gray-800">
                                    <div class="ml-3 flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900 flex items-center justify-center">
                                            @if($type == 'hosting')
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01">
                                                    </path>
                                                </svg>
                                            @elseif($type == 'domain')
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                                    </path>
                                                </svg>
                                            @elseif($type == 'ssl')
                                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                    </path>
                                                </svg>
                                            @elseif($type == 'email')
                                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            @elseif($type == 'server')
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                    </path>
                                                </svg>
                                            @elseif($type == 'license')
                                                <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $config['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Custom Type Input -->
                        <div x-show="selectedTypes.includes('other')" x-transition class="mt-4">
                            <label for="custom_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Hizmet Türü Belirtin *
                            </label>
                            <input type="text" name="custom_type" id="custom_type" value="{{ old('custom_type') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('custom_type') border-danger-500 @enderror"
                                placeholder="Örn: Danışmanlık, Bakım, Yazılım..."
                                :required="selectedTypes.includes('other')">
                            @error('custom_type')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        @error('types')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">İletişim Bilgileri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Website -->
                            <div>
                                <label for="website"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                                <input type="text" name="website" id="website" value="{{ old('website') }}"
                                    placeholder="www.example.com"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-posta</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('email') border-danger-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="md:col-span-2">
                                <label for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    placeholder="0212 123 45 67"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <label for="notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notlar</label>
                        <textarea name="notes" id="notes" rows="4"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Sağlayıcı hakkında notlar...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex items-center justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('providers.index') }}"
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
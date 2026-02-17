<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Sağlayıcı Düzenle" subtitle="{{ $provider->name }}" />

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('providers.update', $provider) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Sağlayıcı Adı *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $provider->name) }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('name') border-danger-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Types (Checkbox Group) -->
                    @php
                        $standardTypes = ['hosting', 'domain', 'ssl', 'email'];
                        $currentTypes = old('types', $provider->types ?? []);
                        $customType = collect($currentTypes)->diff($standardTypes)->first();

                        // If we have a custom type, ensure 'other' is in the UI array
                        $uiTypes = $currentTypes;
                        if ($customType) {
                            $uiTypes = array_diff($uiTypes, [$customType]);
                            if (!in_array('other', $uiTypes)) {
                                $uiTypes[] = 'other';
                            }
                        }
                    @endphp
                    <div x-data="{ 
                        selectedTypes: {{ json_encode(array_values($uiTypes)) }},
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
                            @foreach(['hosting' => ['label' => 'Hosting', 'color' => 'blue'], 'domain' => ['label' => 'Domain', 'color' => 'green'], 'ssl' => ['label' => 'SSL', 'color' => 'yellow'], 'email' => ['label' => 'E-posta', 'color' => 'purple']] as $type => $config)
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
                                            @endif
                                        </div>
                                        <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $config['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach

                            <!-- Other -->
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                :class="selectedTypes.includes('other') ? 'border-gray-500 bg-gray-50 dark:bg-gray-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400'">
                                <input type="checkbox" name="types[]" value="other" @click="toggle('other')"
                                    :checked="selectedTypes.includes('other')"
                                    class="w-4 h-4 text-gray-600 rounded focus:ring-gray-500 dark:focus:ring-gray-600 dark:ring-offset-gray-800">
                                <div class="ml-3 flex items-center space-x-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">Diğer</span>
                                </div>
                            </label>
                        </div>

                        <!-- Custom Type Input -->
                        <div x-show="selectedTypes.includes('other')" x-transition class="mt-4">
                            <label for="custom_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Hizmet Türü Belirtin *
                            </label>
                            <input type="text" name="custom_type" id="custom_type"
                                value="{{ old('custom_type', $customType) }}"
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
                                <input type="text" name="website" id="website"
                                    value="{{ old('website', $provider->website) }}" placeholder="www.example.com"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-posta</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $provider->email) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 @error('email') border-danger-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="md:col-span-2">
                                <label for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $provider->phone) }}"
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
                            placeholder="Sağlayıcı hakkında notlar...">{{ old('notes', $provider->notes) }}</textarea>
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
                            Güncelle
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
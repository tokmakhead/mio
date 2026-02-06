<x-app-layout>
    <!-- Page Banner -->
    <x-page-banner title="Yeni Teklif Oluştur" subtitle="Müşteriniz için detaylı bir teklif formu doldurun." />

    <!-- Main Content -->
    <div class="py-8" x-data="quoteForm()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('quotes.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Basic Info -->
                    <div class="lg:col-span-1 space-y-6">
                        <x-card>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Genel Bilgiler</h3>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Müşteri
                                        *</label>
                                    <select name="customer_id" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">Müşteri Seçin</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Geçerlilik
                                        Tarihi *</label>
                                    <input type="date" name="valid_until"
                                        value="{{ old('valid_until', now()->addDays(30)->format('Y-m-d')) }}" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    @error('valid_until') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Para
                                        Birimi *</label>
                                    <select name="currency" x-model="currency"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                        <option value="TRY">TRY (₺)</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notlar</label>
                                    <textarea name="notes" rows="3"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </x-card>

                        <!-- Summary -->
                        <x-card
                            class="bg-primary-50 dark:bg-primary-900/10 border-primary-100 dark:border-primary-900/20">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Özet</h3>

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Ara Toplam</span>
                                    <span x-text="formatMoney(calculateSubtotal())"></span>
                                </div>
                                <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                                    <span>İndirim</span>
                                    <input type="number" name="discount_total" x-model.number="discount" step="0.01"
                                        min="0"
                                        class="w-24 py-1 text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-xs focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>KDV Toplam</span>
                                    <span x-text="formatMoney(calculateTax())"></span>
                                </div>
                                <div
                                    class="pt-2 border-t border-primary-200 dark:border-primary-800 flex justify-between text-lg font-bold text-primary-700 dark:text-primary-400">
                                    <span>Genel Toplam</span>
                                    <span x-text="formatMoney(calculateGrandTotal())"></span>
                                </div>
                            </div>
                        </x-card>

                        <div class="flex space-x-3">
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-lg shadow-primary-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                                Teklifi Kaydet
                            </button>
                            <a href="{{ route('quotes.index') }}"
                                class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                                İptal
                            </a>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="lg:col-span-2">
                        <x-card>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Teklif Kalemleri</h3>
                                <button type="button" @click="addItem()"
                                    class="text-sm text-primary-600 dark:text-primary-400 flex items-center space-x-1 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Kalem Ekle</span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(item, index) in items" :key="index">
                                    <div
                                        class="p-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30 relative group">
                                        <button type="button" @click="removeItem(index)"
                                            class="absolute -right-2 -top-2 w-6 h-6 bg-danger-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                            <div class="md:col-span-3">
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Açıklama
                                                    / Hizmet</label>
                                                <div class="flex flex-col space-y-2">
                                                    <select :name="`items[${index}][service_id]`"
                                                        @change="onServiceChange(index, $event.target.value)"
                                                        class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                                        <option value="">-- Özel Hizmet --</option>
                                                        @foreach($services as $service)
                                                            <option value="{{ $service->id }}"
                                                                data-price="{{ $service->price }}">{{ $service->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" :name="`items[${index}][description]`"
                                                        x-model="item.description" placeholder="Kalem açıklaması..."
                                                        required
                                                        class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                                </div>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Miktar</label>
                                                <input type="number" :name="`items[${index}][qty]`"
                                                    x-model.number="item.qty" step="0.01" min="0.01" required
                                                    class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Birim
                                                    Fiyat</label>
                                                <input type="number" :name="`items[${index}][unit_price]`"
                                                    x-model.number="item.unit_price" step="0.01" min="0" required
                                                    class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-gray-400 uppercase mb-1">KDV
                                                    %</label>
                                                <select :name="`items[${index}][vat_rate]`"
                                                    x-model.number="item.vat_rate"
                                                    class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                                    <option value="20">20%</option>
                                                    <option value="10">10%</option>
                                                    <option value="1">1%</option>
                                                    <option value="0">0%</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-right text-xs text-gray-500 italic">
                                            Satır Toplamı: <span class="font-bold text-gray-700 dark:text-gray-300"
                                                x-text="formatMoney((item.qty * item.unit_price) * (1 + item.vat_rate/100))"></span>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="items.length === 0"
                                    class="py-12 text-center text-gray-500 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-xl">
                                    Henüz kalem eklenmedi. "Kalem Ekle" butonuna basın.
                                </div>
                            </div>
                        </x-card>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function quoteForm() {
                return {
                    items: [{
                        service_id: '',
                        description: '',
                        qty: 1,
                        unit_price: 0,
                        vat_rate: 20
                    }],
                    discount: 0,
                    currency: 'TRY',

                    addItem() {
                        this.items.push({
                            service_id: '',
                            description: '',
                            qty: 1,
                            unit_price: 0,
                            vat_rate: 20
                        });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                    },

                    onServiceChange(index, serviceId) {
                        if (!serviceId) {
                            this.items[index].service_id = '';
                            return;
                        }

                        const select = event.target;
                        const option = select.options[select.selectedIndex];
                        const price = option.getAttribute('data-price');
                        const name = option.text;

                        this.items[index].service_id = serviceId;
                        this.items[index].unit_price = parseFloat(price);
                        this.items[index].description = name;
                    },

                    calculateSubtotal() {
                        return this.items.reduce((sum, item) => sum + (item.qty * item.unit_price), 0);
                    },

                    calculateTax() {
                        return this.items.reduce((sum, item) => sum + (item.qty * item.unit_price * (item.vat_rate / 100)), 0);
                    },

                    calculateGrandTotal() {
                        return this.calculateSubtotal() + this.calculateTax() - (this.discount || 0);
                    },

                    formatMoney(amount) {
                        let symbol = '₺';
                        if (this.currency === 'USD') symbol = '$';
                        if (this.currency === 'EUR') symbol = '€';

                        return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2 }).format(amount) + ' ' + symbol;
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
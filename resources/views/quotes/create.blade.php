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
                                    <select name="customer_id" id="customer_select" required
                                        placeholder="Müşteri aramaya başlayın..." autocomplete="off">
                                        <option value="">Müşteri Seçiniz...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
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
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">İndirim</span>
                                        <div
                                            class="flex items-center space-x-1 bg-gray-100 dark:bg-gray-800 p-0.5 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <button type="button" @click="discountType = 'fixed'"
                                                :class="discountType === 'fixed' ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                                                class="px-2 py-0.5 text-[9px] font-bold rounded-md transition-all">SABİT</button>
                                            <button type="button" @click="discountType = 'percentage'"
                                                :class="discountType === 'percentage' ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                                                class="px-2 py-0.5 text-[9px] font-bold rounded-md transition-all">YÜZDE
                                                %</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="discount_type" :value="discountType">
                                    <div class="relative">
                                        <input type="number" name="discount_rate" x-model.number="discountRate"
                                            step="0.01" min="0"
                                            class="w-full py-1.5 text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 pr-8">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400 text-xs">
                                            <span
                                                x-text="discountType === 'percentage' ? '%' : (currency === 'TRY' ? '₺' : (currency === 'USD' ? '$' : '€'))"></span>
                                        </div>
                                    </div>
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
                                                                data-price="{{ $service->price }}"
                                                                data-currency="{{ $service->currency }}"
                                                                data-vat-rate="{{ $service->vat_rate ?? 20 }}"
                                                                data-description-template="{{ $service->description_template }}">
                                                                {{ $service->name }} ({{ $service->price }}
                                                                {{ $service->currency }})
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (document.getElementById('customer_select')) {
                    new TomSelect('#customer_select', {
                        valueField: 'id',
                        labelField: 'text',
                        searchField: ['name', 'email', 'tax_number'],
                        preload: true,
                        load: function (query, callback) {
                            var url = '{{ route("customers.api_search") }}?q=' + encodeURIComponent(query);
                            fetch(url)
                                .then(response => response.json())
                                .then(json => {
                                    callback(json);
                                }).catch(() => {
                                    callback();
                                });
                        },
                        placeholder: 'Müşteri adı, e-posta veya vergi no...',
                        render: {
                            option: function (item, escape) {
                                return `<div>
                                                    <span class="font-bold">${escape(item.name)}</span>
                                                    <span class="text-xs text-gray-500 block">${escape(item.email)}</span>
                                                </div>`;
                            },
                            item: function (item, escape) {
                                return `<div>${escape(item.name)}</div>`;
                            }
                        }
                    });
                }
            });

            function quoteForm() {
                return {
                    items: [{
                        service_id: '',
                        description: '',
                        qty: 1,
                        unit_price: 0,
                        vat_rate: 20
                    }],
                    discountRate: 0,
                    discountType: 'fixed',
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
                        const price = parseFloat(option.getAttribute('data-price'));
                        const name = option.text.split('(')[0].trim();
                        const serviceCurrency = option.getAttribute('data-currency');
                        const vatRate = parseInt(option.getAttribute('data-vat-rate')) || 20;
                        const descriptionTemplate = option.getAttribute('data-description-template');

                        // Currency Check
                        if (serviceCurrency && serviceCurrency !== this.currency) {
                            alert(`DİKKAT: Seçilen hizmetin para birimi (${serviceCurrency}) ile teklif para birimi (${this.currency}) farklı!\n\nFiyatı (${price}) kur çevrimine göre manuel olarak güncellemeniz önerilir.`);
                        }

                        this.items[index].service_id = serviceId;
                        this.items[index].unit_price = price;
                        this.items[index].vat_rate = vatRate;

                        // Use description template if available, otherwise default to service name
                        if (descriptionTemplate && descriptionTemplate.trim() !== '') {
                            this.items[index].description = descriptionTemplate;
                        } else {
                            this.items[index].description = name;
                        }
                    },

                    calculateSubtotal() {
                        return this.items.reduce((sum, item) => sum + (item.qty * item.unit_price), 0);
                    },

                    calculateTax() {
                        return this.items.reduce((sum, item) => sum + (item.qty * item.unit_price * (item.vat_rate / 100)), 0);
                    },

                    calculateDiscountAmount() {
                        if (this.discountType === 'percentage') {
                            return (this.calculateSubtotal() * (this.discountRate / 100));
                        }
                        return this.discountRate || 0;
                    },

                    calculateGrandTotal() {
                        return this.calculateSubtotal() + this.calculateTax() - this.calculateDiscountAmount();
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
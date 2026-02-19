<x-app-layout>
    <x-page-banner title="Yeni Fatura Oluştur" subtitle="Müşteriniz için manuel fatura kaydı yapın." />

    <div class="py-12" x-data="invoiceForm()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('invoices.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Sidebar: Meta Information -->
                    <div class="lg:col-span-1 space-y-6">
                        <x-card>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-tighter">
                                Genel Bilgiler</h3>

                            <div class="space-y-5">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Müşteri
                                        Seçin *</label>
                                    <select name="customer_id" id="customer_select" required
                                        placeholder="Müşteri aramaya başlayın..." autocomplete="off">
                                        <option value="">Müşteri Seçiniz...</option>
                                    </select>
                                    @error('customer_id') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Düz.
                                            Tarihi *</label>
                                        <input type="date" name="issue_date"
                                            value="{{ old('issue_date', now()->format('Y-m-d')) }}" required
                                            class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                                        @error('issue_date') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Vade
                                            Tarihi *</label>
                                        <input type="date" name="due_date"
                                            value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required
                                            class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                                        @error('due_date') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Para
                                        Birimi *</label>
                                    <select name="currency" x-model="currency"
                                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                        <option value="TRY">TRY (₺)</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                    </select>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest">İndirim</label>
                                        <div
                                            class="flex items-center space-x-2 bg-gray-100 dark:bg-gray-800 p-0.5 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <button type="button" @click="discountType = 'fixed'"
                                                :class="discountType === 'fixed' ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                                                class="px-2 py-0.5 text-[10px] font-bold rounded-md transition-all">SABİT</button>
                                            <button type="button" @click="discountType = 'percentage'"
                                                :class="discountType === 'percentage' ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                                                class="px-2 py-0.5 text-[10px] font-bold rounded-md transition-all">YÜZDE
                                                %</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="discount_type" :value="discountType">
                                    <div class="relative">
                                        <input type="number" name="discount_rate" x-model.number="discountRate"
                                            step="0.01" min="0"
                                            class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm pr-10">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                            <span
                                                x-text="discountType === 'percentage' ? '%' : (currency === 'TRY' ? '₺' : (currency === 'USD' ? '$' : '€'))"></span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Notlar</label>
                                    <textarea name="notes" rows="4" placeholder="Kayıt notları..."
                                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </x-card>

                        <!-- Final Totals Card -->
                        <x-card
                            class="bg-primary-50 dark:bg-primary-900/5 border-primary-100 dark:border-primary-900/10">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-tighter">
                                Hesap Özeti</h3>

                            <div class="space-y-4">
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>Ara Toplam</span>
                                    <span x-text="formatMoney(calculateSubtotal())"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>KDV Toplam</span>
                                    <span x-text="formatMoney(calculateTax())"></span>
                                </div>
                                <div class="flex justify-between text-sm text-danger-600 font-semibold"
                                    x-show="calculateDiscountAmount() > 0">
                                    <span>İndirim</span>
                                    <span x-text="'-' + formatMoney(calculateDiscountAmount())"></span>
                                </div>
                                <div
                                    class="pt-4 border-t border-primary-100 dark:border-primary-900 flex justify-between">
                                    <span
                                        class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Genel
                                        Toplam</span>
                                    <span class="text-xl font-black text-primary-600"
                                        x-text="formatMoney(calculateGrandTotal())"></span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full mt-8 px-6 py-4 bg-primary-600 hover:bg-primary-700 text-white font-black uppercase tracking-widest rounded-xl shadow-xl shadow-primary-500/20 transition-all transform hover:-translate-y-1 active:translate-y-0">
                                Faturayı Kaydet
                            </button>
                            <a href="{{ route('invoices.index') }}"
                                class="block text-center mt-4 text-xs font-bold text-gray-400 uppercase hover:text-gray-600 transition-colors">Vazgeç</a>
                        </x-card>
                    </div>

                    <!-- Main Area: Items -->
                    <div class="lg:col-span-2">
                        <x-card>
                            <div
                                class="flex justify-between items-center mb-8 pb-4 border-b border-gray-50 dark:border-gray-800">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tighter">
                                    Fatura Kalemleri</h3>
                                <button type="button" @click="addItem()"
                                    class="inline-flex items-center px-4 py-2 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-600 font-bold rounded-lg transition-colors border border-gray-100 dark:border-gray-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Kalem Ekle
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(item, index) in items" :key="index">
                                    <div
                                        class="p-6 rounded-2xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 relative group transition-all hover:bg-white dark:hover:bg-gray-800/40 hover:border-primary-100 dark:hover:border-primary-900">
                                        <!-- Remove Button -->
                                        <button type="button" @click="removeItem(index)"
                                            class="absolute -right-2 -top-2 w-8 h-8 bg-danger-500 text-white rounded-full flex items-center justify-center shadow-lg transform scale-0 group-hover:scale-100 transition-transform duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                            <!-- Description / Service -->
                                            <div class="md:col-span-6">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Hizmet
                                                    Tanımı</label>
                                                <div class="space-y-2">
                                                    <select x-model="item.service_id"
                                                        @change="onServiceChange(index, $event.target.value)"
                                                        class="w-full text-sm font-bold rounded-xl border-gray-100 dark:border-gray-800 dark:bg-gray-900/50 focus:ring-primary-500">
                                                        <option value="">-- Özel Giriş --</option>
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
                                                        class="w-full text-sm rounded-xl border-gray-100 dark:border-gray-800 dark:bg-gray-900/50">
                                                </div>
                                            </div>

                                            <!-- Qty -->
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Miktar</label>
                                                <input type="number" :name="`items[${index}][qty]`"
                                                    x-model.number="item.qty" step="0.01" min="0.01" required
                                                    class="w-full text-sm font-bold rounded-xl border-gray-100 dark:border-gray-800 dark:bg-gray-900/50">
                                            </div>

                                            <!-- Price -->
                                            <div class="md:col-span-3">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Birim
                                                    Fiyat</label>
                                                <input type="number" :name="`items[${index}][unit_price]`"
                                                    x-model.number="item.unit_price" step="0.01" min="0" required
                                                    class="w-full text-sm font-bold rounded-xl border-gray-100 dark:border-gray-800 dark:bg-gray-900/50">
                                            </div>

                                            <!-- VAT -->
                                            <div class="md:col-span-1">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 text-center">KDV</label>
                                                <select :name="`items[${index}][vat_rate]`"
                                                    x-model.number="item.vat_rate"
                                                    class="w-full text-xs font-bold rounded-xl border-gray-100 dark:border-gray-800 dark:bg-gray-900/50 px-2">
                                                    <option value="20">20%</option>
                                                    <option value="10">10%</option>
                                                    <option value="1">1%</option>
                                                    <option value="0">0%</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-4 flex justify-end items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            <span class="mr-2">Satır Toplam:</span>
                                            <span
                                                class="text-sm font-black text-gray-700 dark:text-gray-300 tracking-tighter"
                                                x-text="formatMoney((item.qty * item.unit_price) * (1 + item.vat_rate/100))"></span>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="items.length === 0"
                                    class="py-20 text-center border-4 border-dashed border-gray-50 dark:border-gray-800/50 rounded-3xl">
                                    <div class="text-gray-400 font-bold uppercase tracking-widest text-xs">Henüz kalem
                                        eklenmedi.</div>
                                    <button type="button" @click="addItem()"
                                        class="mt-4 text-primary-600 font-black hover:underline tracking-tighter text-sm uppercase">Hemen
                                        Kalem Ekle +</button>
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
            document.addEventListener('DOMContentLoaded', function () {
                if (document.getElementById('customer_select')) {
                    new TomSelect('#customer_select', {
                        valueField: 'id',
                        labelField: 'text',
                        searchField: ['name', 'email', 'tax_number'],
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

            function invoiceForm() {
                return {
                    items: [{ service_id: '', description: '', qty: 1, unit_price: 0, vat_rate: 20 }],
                    currency: 'TRY',
                    discountRate: 0,
                    discountType: 'fixed',

                    addItem() {
                        this.items.push({ service_id: '', description: '', qty: 1, unit_price: 0, vat_rate: 20 });
                    },
                    removeItem(index) { this.items.splice(index, 1); },
                    onServiceChange(index, serviceId) {
                        if (!serviceId) return;
                        const select = event.target;
                        const option = select.options[select.selectedIndex];
                        let price = parseFloat(option.getAttribute('data-price'));
                        const serviceCurrency = option.getAttribute('data-currency');
                        const vatRate = parseInt(option.getAttribute('data-vat-rate')) || 20;
                        const descriptionTemplate = option.getAttribute('data-description-template');

                        // Currency Check
                        if (serviceCurrency && serviceCurrency !== this.currency) {
                            alert(`DİKKAT: Seçilen hizmetin para birimi (${serviceCurrency}) ile fatura para birimi (${this.currency}) farklı!\n\nFiyatı (${price}) kur çevrimine göre manuel olarak güncellemeniz önerilir.`);
                        }

                        this.items[index].unit_price = price;
                        this.items[index].vat_rate = vatRate;

                        // Use description template if available, otherwise default to service name
                        if (descriptionTemplate && descriptionTemplate.trim() !== '') {
                            this.items[index].description = descriptionTemplate;
                        } else {
                            this.items[index].description = option.text.split('(')[0].trim();
                        }
                    },
                    calculateSubtotal() { return this.items.reduce((sum, item) => sum + (item.qty * item.unit_price), 0); },
                    calculateTax() { return this.items.reduce((sum, item) => sum + (item.qty * item.unit_price * (item.vat_rate / 100)), 0); },
                    calculateDiscountAmount() {
                        if (this.discountType === 'percentage') {
                            return (this.calculateSubtotal() * (this.discountRate / 100));
                        }
                        return this.discountRate || 0;
                    },
                    calculateGrandTotal() { return this.calculateSubtotal() + this.calculateTax() - this.calculateDiscountAmount(); },
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
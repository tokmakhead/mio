@extends('master.layout')

@section('content')
    <div class="mb-8">
        <a href="{{ route('master.announcements.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Listeye Dön</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Duyuru Ekle</h1>
    </div>

    <div class="max-w-3xl">
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('master.announcements.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duyuru
                                Başlığı</label>
                            <input type="text" name="title" id="title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duyuru
                                Tipi</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="typeSelector">
                                <!-- Info -->
                                <label class="relative cursor-pointer group" onclick="selectType(this)">
                                    <input type="radio" name="type" value="info" class="sr-only" checked>
                                    <div
                                        class="p-4 rounded-lg border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20 transition-all text-center h-full flex flex-col justify-center selection-box">
                                        <div class="font-bold mb-1 text-blue-600 dark:text-blue-400">Bilgi</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Mavi</div>
                                    </div>
                                    <div class="absolute top-2 right-2 text-blue-500 check-icon">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </label>

                                <!-- Success -->
                                <label class="relative cursor-pointer group" onclick="selectType(this)">
                                    <input type="radio" name="type" value="success" class="sr-only">
                                    <div
                                        class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-center h-full flex flex-col justify-center selection-box">
                                        <div class="font-bold mb-1 text-green-600 dark:text-green-400">Başarı</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Yeşil</div>
                                    </div>
                                    <div class="absolute top-2 right-2 text-green-500 hidden check-icon">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </label>

                                <!-- Warning -->
                                <label class="relative cursor-pointer group" onclick="selectType(this)">
                                    <input type="radio" name="type" value="warning" class="sr-only">
                                    <div
                                        class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-center h-full flex flex-col justify-center selection-box">
                                        <div class="font-bold mb-1 text-yellow-600 dark:text-yellow-400">Uyarı</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Sarı</div>
                                    </div>
                                    <div class="absolute top-2 right-2 text-yellow-500 hidden check-icon">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </label>

                                <!-- Danger -->
                                <label class="relative cursor-pointer group" onclick="selectType(this)">
                                    <input type="radio" name="type" value="danger" class="sr-only">
                                    <div
                                        class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-center h-full flex flex-col justify-center selection-box">
                                        <div class="font-bold mb-1 text-red-600 dark:text-red-500">Kritik</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Kırmızı</div>
                                    </div>
                                    <div class="absolute top-2 right-2 text-red-500 hidden check-icon">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Target & Mode -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div>
                                <label for="target_type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hedef Kitle</label>
                                <select name="target_type" id="target_type" onchange="toggleLicenseSelect()"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="all">Tüm Müşteriler (Global)</option>
                                    <option value="license">Belirli Bir Lisans</option>
                                </select>
                            </div>

                            <div id="license_select_container" class="hidden">
                                <label for="master_license_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hedef Lisans</label>
                                <select name="master_license_id" id="master_license_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach($licenses as $lic)
                                        <option value="{{ $lic->id }}">{{ $lic->code }} ({{ $lic->client_name }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="display_mode"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Görünüm Modu</label>
                                <select name="display_mode" id="display_mode"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="banner">Banner (Üst Bant)</option>
                                    <option value="modal">Modal (Popup Pencere)</option>
                                    <option value="feed">Feed (Haber Akışı)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Flags -->
                        <div class="flex flex-wrap gap-6">
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" checked value="1"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Aktif
                                    Yayın</label>
                            </div>
                            <div class="flex items-center">
                                <input id="is_dismissible" name="is_dismissible" type="checkbox" checked value="1"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_dismissible"
                                    class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Kapatılabilir</label>
                            </div>
                            <div
                                class="flex items-center px-4 py-2 border border-red-200 bg-red-50 dark:bg-red-900/10 rounded-lg">
                                <input id="is_priority" name="is_priority" type="checkbox" value="1"
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="is_priority"
                                    class="ml-2 block text-sm font-bold text-red-700 dark:text-red-400">🚨 Flaş Haber /
                                    Kritik Duyuru</label>
                            </div>
                        </div>

                        <!-- Javascript for reliable selection -->
                        <script>
                            function toggleLicenseSelect() {
                                const type = document.getElementById('target_type').value;
                                const container = document.getElementById('license_select_container');
                                if (type === 'license') {
                                    container.classList.remove('hidden');
                                } else {
                                    container.classList.add('hidden');
                                }
                            }

                            function selectType(label) {
                                // Reset all
                                document.querySelectorAll('#typeSelector label').forEach(lbl => {
                                    const box = lbl.querySelector('.selection-box');
                                    const icon = lbl.querySelector('.check-icon');
                                    box.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20', 'border-green-500', 'bg-green-50', 'dark:bg-green-900/20', 'border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20', 'border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                                    box.classList.add('border-gray-200', 'dark:border-gray-700');
                                    icon.classList.add('hidden');
                                });

                                // Set Active
                                const box = label.querySelector('.selection-box');
                                const icon = label.querySelector('.check-icon');
                                const input = label.querySelector('input');
                                const val = input.value;

                                box.classList.remove('border-gray-200', 'dark:border-gray-700');
                                icon.classList.remove('hidden');

                                if (val === 'info') box.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                                if (val === 'success') box.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                                if (val === 'warning') box.classList.add('border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20');
                                if (val === 'danger') box.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                            }
                        </script>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duyuru
                            Metni</label>
                        <textarea name="message" id="message" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required></textarea>
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Başlangıç
                                Tarihi</label>
                            <input type="date" name="start_date" id="start_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500">Boş bırakılırsa hemen yayınlanır.</p>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bitiş
                                Tarihi</label>
                            <input type="date" name="end_date" id="end_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500">Boş bırakılırsa süresiz kalır.</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Duyuruyu Yayınla
                        </button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection
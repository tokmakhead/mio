@extends('master.layout')

@section('content')
    <div class="mb-8">
        <a href="{{ route('master.licenses.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Listeye Dön</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Lisans Oluştur</h1>
    </div>

    <div class="max-w-2xl">
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('master.licenses.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Client Name -->
                        <div>
                            <label for="client_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri Adı / Firma
                                Ünvanı</label>
                            <input type="text" name="client_name" id="client_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                        </div>

                        <!-- Client Email -->
                        <div>
                            <label for="client_email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri E-Posta</label>
                            <input type="email" name="client_email" id="client_email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Lisans bilgileri bu adrese gönderilmez, sadece kayıt
                                içindir.</p>
                        </div>

                        <!-- License Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lisans
                                Tipi</label>
                            <select name="type" id="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="standard">Standart Lisans (Tek Domain)</option>
                                <option value="extended">Genişletilmiş Lisans (Çoklu Domain)</option>
                                <option value="monthly">Aylık Kiralama</option>
                            </select>
                        </div>

                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                <strong>Not:</strong> Lisans kodu (License Key) otomatik olarak
                                <code>MIO-XXXX-YYYY-ZZZZ</code> formatında oluşturulacaktır.
                            </p>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Lisansı Üret ve Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
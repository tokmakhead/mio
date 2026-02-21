@extends('master.layout')

@section('content')
    <div class="mb-8">
        <a href="{{ route('master.admins.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Listeye Dön</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Yönetici Ekle</h1>
    </div>

    <div class="max-w-2xl">
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('master.admins.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad
                                Soyad</label>
                            <input type="text" name="name" id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email
                                Adresi</label>
                            <input type="email" name="email" id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                        </div>

                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Şifre</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                        </div>

                        <div>
                            <label for="master_role"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Master Rolü</label>
                            <select name="master_role" id="master_role"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                                <option value="super_admin">Super Admin (Tam Yetki)</option>
                                <option value="manager">Manager (Lisans & Sürüm)</option>
                                <option value="support">Support (Duyuru & İzleme)</option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500 line-clamp-2">
                                <strong>Super Admin:</strong> Her şeyi yapabilir.<br>
                                <strong>Manager:</strong> Lisansları ve sürümleri yönetebilir.<br>
                                <strong>Support:</strong> Duyuruları yönetebilir ve lisansları görebilir.
                            </p>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Yöneticiyi Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
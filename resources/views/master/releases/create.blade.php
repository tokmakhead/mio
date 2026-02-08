@extends('master.layout')

@section('content')
    <div class="mb-8">
        <a href="{{ route('master.releases.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Listeye Dön</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Sürüm Yayınla</h1>
    </div>

    <div class="max-w-3xl">
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('master.releases.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Version -->
                            <div>
                                <label for="version"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sürüm
                                    Numarası</label>
                                <input type="text" name="version" id="version" placeholder="v1.1.0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                <p class="mt-1 text-xs text-gray-500">Semantik versiyonlama kullanın (Örn: v1.0.1)</p>
                            </div>

                            <!-- File Path -->
                            <div>
                                <label for="file_path"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosya Yolu /
                                    URL</label>
                                <input type="text" name="file_path" id="file_path"
                                    placeholder="/uploads/releases/v1.1.0.zip"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                <p class="mt-1 text-xs text-gray-500">Güncelleme paketinin (ZIP) sunucudaki tam yolu.</p>
                            </div>
                        </div>

                        <!-- Critical Update -->
                        <div class="flex items-center">
                            <input id="is_critical" name="is_critical" type="checkbox"
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="is_critical" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                Bu kritik bir güvenlik güncellemesidir (Zorunlu)
                            </label>
                        </div>

                        <!-- Release Notes -->
                        <div>
                            <label for="release_notes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sürüm Notları</label>
                            <textarea name="release_notes" id="release_notes" rows="6"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="<ul><li>Yeni özellik eklendi...</li><li>Hata düzeltildi...</li></ul>"></textarea>
                            <p class="mt-1 text-xs text-gray-500">HTML formatı desteklenir. Müşteri panelinde bu notlar
                                görünecektir.</p>
                        </div>

                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Dikkat</h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>Yayınla butonuna bastığınız anda bu güncelleme tüm aktif lisanslı panellere
                                            bildirim olarak düşecektir. Sürüm notlarını kontrol ettiğinizden emin olun.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Sürümü Yayınla
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
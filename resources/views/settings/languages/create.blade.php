<x-app-layout>
    <x-page-banner title="Yeni Dil Ekle" subtitle="Sisteme yeni bir dil desteği ekleyin." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form action="{{ route('settings.languages.store') }}" method="POST" class="p-6">
                    @csrf

                    <div>
                        <x-input-label for="locale" value="Dil Kodu (Örn: tr, fr, de)" />
                        <x-text-input id="locale" name="locale" type="text" class="mt-1 block w-full uppercase"
                            maxlength="2" required autofocus />
                        <x-input-error :messages="$errors->get('locale')" class="mt-2" />
                        <p class="mt-2 text-xs text-gray-500">2 karakterli ISO kodu giriniz. 'en.json' dosyasından
                            kopyalanarak oluşturulacaktır.</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button type="button" onclick="history.back()" class="mr-3">
                            İptal
                        </x-secondary-button>
                        <x-primary-button>
                            Oluştur
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
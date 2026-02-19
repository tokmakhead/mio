<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Dil Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-settings-tabs />

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('settings.languages.store') }}" method="POST" class="max-w-md">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="locale" :value="__('Dil Kodu (Örn: fr, es)')" />
                            <x-text-input id="locale" class="block mt-1 w-full" type="text" name="locale" required
                                autofocus />
                            <x-input-error :messages="$errors->get('locale')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Oluştur') }}</x-primary-button>
                            <a href="{{ route('settings.languages.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('İptal') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
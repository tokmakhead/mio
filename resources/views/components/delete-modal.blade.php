@props(['title' => 'Emin misiniz?', 'message' => 'Bu işlem geri alınamaz.', 'confirmText' => 'Sil', 'cancelText' => 'İptal', 'action', 'method' => 'DELETE'])

<div x-data="{ open: false }">
    <!-- Trigger Button -->
    <button @click="window.Mionex.isDemo ? window.showDemoAlert() : open = true" type="button" {{ $attributes->merge(['class' => $slot->isEmpty() ? 'inline-flex items-center justify-center w-8 h-8 bg-rose-50/50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-lg transition-all duration-200 hover:scale-110 hover:bg-rose-100 dark:hover:bg-rose-900/40' : '']) }}>
        @if($slot->isEmpty())
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        @else
            {{ $slot }}
        @endif
    </button>

    <!-- Modal Overlay -->
    <div x-show="open" x-cloak @click="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-200"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <!-- Modal Content -->
        <div @click.stop class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden"
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <!-- Icon -->
            <div class="p-6 pb-4">
                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto bg-danger-100 dark:bg-danger-900/30 rounded-full">
                    <svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
            </div>

            <!-- Title & Message -->
            <div class="px-6 pb-4 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $message }}</p>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 flex items-center justify-end space-x-3">
                <button @click="open = false" type="button"
                    class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                    {{ $cancelText }}
                </button>

                <form action="{{ $action }}" method="POST" class="inline">
                    @csrf
                    @method($method)
                    <button type="submit"
                        class="px-4 py-2 bg-danger-600 hover:bg-danger-700 text-white rounded-lg transition-colors duration-200">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@props(['title' => 'Emin misiniz?', 'message' => 'Bu işlem geri alınamaz.', 'confirmText' => 'Sil', 'cancelText' => 'İptal', 'action', 'method' => 'DELETE'])

<div x-data="{ open: false }">
    <!-- Trigger Button -->
    <button @click="open = true" type="button" {{ $attributes->merge(['class' => 'p-2 text-gray-600 hover:text-danger-600 dark:text-gray-400 dark:hover:text-danger-400 transition-colors']) }}>
        {{ $slot }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
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
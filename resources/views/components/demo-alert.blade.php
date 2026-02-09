<div x-data="{ open: false }" @show-demo-alert.window="open = true" x-cloak>
    <!-- Modal Overlay -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <!-- Modal Content -->
        <div @click.away="open = false"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100 dark:border-gray-700 transform transition-all"
            x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 translate-y-4">

            <div class="relative p-8 text-center">
                <!-- Close Button -->
                <button @click="open = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <!-- Icon -->
                <div
                    class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z">
                        </path>
                    </svg>
                </div>

                <!-- Content -->
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 uppercase tracking-tight">Demo Sürümü
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Şu an **Demo Modundasınız.** Sistemin tüm özelliklerini inceleyebilirsiniz ancak güvenlik gereği
                    veri kaydetme, düzenleme veya silme işlemleri **deaktiftir.**
                </p>

                <!-- Action -->
                <button @click="open = false"
                    class="w-full py-4 bg-gray-900 dark:bg-white text-white dark:text-black font-bold rounded-xl hover:scale-[1.02] active:scale-95 transition-all duration-200 shadow-xl shadow-black/10">
                    Anladım, İncelemeye Devam Et
                </button>
            </div>

            <!-- Bottom Accent -->
            <div class="h-1.5 bg-gradient-to-r from-amber-400 via-amber-600 to-amber-400"></div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
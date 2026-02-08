<footer
    class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-colors duration-200 mt-auto">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <!-- Left Side: App Name & Copyright -->
            <div class="flex items-center space-x-2">
                <span class="text-xl font-black tracking-tighter text-primary-600">
                    {{ $siteSettings->site_name ?? config('app.name', 'MIOLY') }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} Tüm hakları saklıdır.
                </span>
            </div>

            <!-- Middle: Protective Text -->
            <div
                class="flex items-center text-sm font-semibold text-gray-400 dark:text-gray-500 px-4 py-1 rounded-full bg-gray-50 dark:bg-gray-900/50">
                We <span class="text-rose-500 mx-1">❤️</span> MIONEX
            </div>

            <!-- Right Side: Version & Status -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-1.5">
                    <span
                        class="flex-shrink-0 w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] animate-blink"></span>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">Sistem
                        Aktif</span>
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500 font-mono">
                    v1.0
                </div>
            </div>
        </div>
    </div>
</footer>
@props(['title', 'subtitle' => '', 'metric' => '', 'label' => '', 'tooltip' => ''])

<div
    class="bg-gradient-to-r from-primary-700 to-primary-900 dark:from-primary-800 dark:to-primary-900 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-1 text-accent-100 dark:text-accent-200">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-6">
                @if(isset($actions))
                    <div>
                        {{ $actions }}
                    </div>
                @endif
                @if($metric)
                    <div class="hidden md:flex flex-col items-end">
                        @if($label)
                            <div class="flex items-center text-accent-200 text-xs font-medium mb-1 group relative">
                                <span>{{ $label }}</span>
                                @if($tooltip)
                                    <svg class="w-3 h-3 ml-1 cursor-help opacity-60 hover:opacity-100 transition-opacity"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div
                                        class="absolute bottom-full right-0 mb-2 w-48 p-2 bg-gray-900 text-white text-[10px] rounded shadow-xl opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50">
                                        {{ $tooltip }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 backdrop-blur-md text-white font-bold text-lg border border-white/20">
                            {{ $metric }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
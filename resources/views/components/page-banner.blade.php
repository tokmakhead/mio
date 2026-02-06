@props(['title', 'subtitle' => '', 'metric' => ''])

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
            @if($metric)
                <div class="hidden md:block">
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm text-white font-semibold">
                        {{ $metric }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
@props(['title', 'value', 'icon' => '', 'tone' => 'primary', 'subValue' => ''])

@php
    $toneClasses = [
        'primary' => 'border-primary-500',
        'success' => 'border-success-500',
        'warning' => 'border-warning-500',
        'danger' => 'border-danger-500',
    ];
    $borderClass = $toneClasses[$tone] ?? $toneClasses['primary'];
@endphp

<div
    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-l-4 {{ $borderClass }} p-6 transition-all duration-200 hover:shadow-md">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
            @if($subValue)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $subValue }}</p>
            @endif
        </div>
        @if($icon)
            <div class="flex-shrink-0">
                <div
                    class="w-12 h-12 rounded-lg bg-{{ $tone }}-50 dark:bg-{{ $tone }}-900/20 flex items-center justify-center">
                    {!! $icon !!}
                </div>
            </div>
        @endif
    </div>
</div>
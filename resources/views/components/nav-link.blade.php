@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'px-2 py-2 rounded-lg text-sm font-medium bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 transition-all duration-200 whitespace-nowrap'
        : 'px-2 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all duration-200 whitespace-nowrap';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
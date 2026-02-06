@props(['padding' => 'p-6'])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-lg shadow-sm ' . $padding . ' transition-colors duration-200']) }}>
    {{ $slot }}
</div>
@if(isset($brandSettings['logo_path']))
    <img src="{{ $brandSettings['logo_path'] }}" alt="{{ config('app.name') }}" {{ $attributes }}>
@else
    <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
        <rect width="50" height="50" rx="10" fill="currentColor" class="text-primary-600 dark:text-primary-500" />
        <path d="M15 35V15L25 25L35 15V35" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
@endif
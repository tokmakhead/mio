<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteSettings->site_name ?? config('app.name', 'MIOLY') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Dynamic Favicon -->
    @if(isset($brandSettings['favicon_path']))
        <link rel="icon" href="{{ $brandSettings['favicon_path'] }}" type="image/x-icon" />
    @endif

    <!-- Dynamic Colors -->
    @if(isset($brandSettings['primary_color']))
        @php
            $hex = $brandSettings['primary_color'];
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            $rgb = "$r $g $b";
        @endphp
        <style>
            :root {
                --color-primary-500:
                    {{ $rgb }}
                ;
                --color-primary-600:
                    {{ $rgb }}
                ;
                /* Using same for simplicity, or darken slightly */
                /* For a full palette we would need a sophisticated generator, 
                                           but overriding 500/600 covers buttons and main accents */
                --color-primary-50:
                    {{ $r }}
                    {{ $g }}
                    {{ $b }}
                    / 0.1;
            }
        </style>
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.Mionex = {
            isDemo: {{ auth() -> check() && auth() -> user() -> demo_readonly && !in_array(auth() -> user() -> email, ['admin@mioly.test', 'admin@mionex.com']) ? 'true' : 'false' }}
        };

        window.showDemoAlert = function () {
            window.dispatchEvent(new CustomEvent('show-demo-alert', {
                bubbles: true,
                cancelable: true,
                detail: {}
            }));
        };

        // Global Form Protection for Demo
        document.addEventListener('submit', function (e) {
            if (window.Mionex.isDemo) {
                // Ignore logout
                if (e.target.getAttribute('action') && e.target.getAttribute('action').includes('logout')) return;

                e.preventDefault();
                e.stopPropagation();
                window.showDemoAlert();
                return false;
            }
        }, true);

        // Specific Alert for Demo Restrict (links that perform actions but aren't forms)
        document.addEventListener('click', function (e) {
            if (window.Mionex.isDemo) {
                const target = e.target.closest('.demo-restrict');
                // Only intercept if it's NOT a submit button (those are handled by the submit listener)
                // AND it's not a form itself
                if (target && target.tagName !== 'FORM' && target.getAttribute('type') !== 'submit') {
                    e.preventDefault();
                    e.stopPropagation();
                    window.showDemoAlert();
                    return false;
                }
            }
        }, true);
    </script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <x-topnav />

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <x-footer />
    </div>

    <!-- Demo Alert Modal -->
    <x-demo-alert />

    <!-- Toast Notifications -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-success-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-danger-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            {{ session('error') }}
        </div>
    @endif

    @stack('scripts')
</body>

</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MIONEX') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

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
                --color-primary-50:
                    {{ $r }}
                    {{ $g }}
                    {{ $b }}
                    / 0.1;
                --color-primary-900:
                    {{ $r }}
                    {{ $g }}
                    {{ $b }}
                ;
                /* For auth gradient */
            }

            /* Override tailwind classes if needed for specificity */
            .bg-primary-600 {
                background-color: rgb({{ $rgb }}) !important;
            }

            .text-primary-600 {
                color: rgb({{ $rgb }}) !important;
            }
        </style>
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-gray-900">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Side: Brand & Visual -->
        <div class="relative hidden lg:flex flex-col justify-between bg-zinc-900 text-white p-12 overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0">
                <img src="{{ $brandSettings['login_image_path'] ?? 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop' }}"
                    alt="Background" class="w-full h-full object-cover opacity-40 mix-blend-overlay">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-900/90 to-zinc-900/90 mix-blend-multiply">
                </div>
            </div>

            <!-- Content -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <!-- Logo Placeholder -->
                    <div
                        class="w-10 h-10 rounded-lg bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-white">{{ config('app.name') }}</span>
                </div>
            </div>

            <div class="relative z-10 max-w-md">
                <blockquote class="space-y-6">
                    <p class="text-lg font-medium leading-relaxed text-white/90">
                        "İş süreçlerinizi modernize eden, finansal akışınızı hızlandıran ve müşteri ilişkilerinizi
                        güçlendiren yeni nesil yönetim platformu."
                    </p>
                    <footer class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <cite class="text-sm font-semibold not-italic text-white">MIONEX Ekibi</cite>
                            <span class="text-xs text-zinc-400">Ürün Geliştirme</span>
                        </div>
                    </footer>
                </blockquote>
            </div>

            <div class="relative z-10 text-xs text-zinc-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Tüm hakları saklıdır.
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white dark:bg-gray-900">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-10 text-center">
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">
                        {{ config('app.name') }}
                    </h1>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>